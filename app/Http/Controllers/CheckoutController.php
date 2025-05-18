<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Obat;
use App\Models\MetodeBayar;
use App\Models\JenisPengiriman;
use App\Models\Pelanggan;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    protected $midtransMethodId;
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        $this->midtransMethodId = MetodeBayar::where('metode_pembayaran', 'Midtrans')->value('id');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'metode_bayar' => 'required|exists:metode_bayars,id',
            'alamat_pengiriman' => 'required|string',
        ]);

        $penjualan = Penjualan::create([
            'id_pelanggan' => Auth::guard('pelanggan')->id(),
            'id_metode_bayar' => $request->metode_bayar,
            'total_bayar' => $this->calculateTotal($request->obat),
            'status_order' => 'Pending',
            'alamat_pengiriman' => $request->alamat_pengiriman,
        ]);

        foreach ($request->obat as $item) {
            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id,
                'id_obat' => $item['id_obat'],
                'jumlah_beli' => $item['jumlah'],
                'harga_beli' => $item['harga'],
                'subtotal' => $item['jumlah'] * $item['harga'],
            ]);
        }

        return $this->processMidtransPayment($penjualan, $request->obat);
    }

    public function processCheckout(Request $request)
    {
    try {
        $validated = $request->validate([
            'id_metode_bayar' => 'required|exists:metode_bayars,id',
            'id_jenis_kirim' => 'required|exists:jenis_pengirimans,id',
            'alamat_pengiriman' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:500',
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:keranjangs,id'
        ]);

        $pelangganId = session('loginId');
        $selectedItems = $request->input('selected_items', []);
        
        $keranjangItems = Keranjang::with('obat')
            ->where('id_pelanggan', $pelangganId) 
            ->whereIn('id', $selectedItems)
            ->get();

        if ($keranjangItems->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Tidak ada item yang dipilih untuk checkout');
        }

        // Calculate totals
        $subtotal = $keranjangItems->sum('subtotal');
        $jenisPengiriman = JenisPengiriman::find($request->id_jenis_kirim);
        $ongkosKirim = $jenisPengiriman->harga;
        $biayaApp = 0;
        $totalBayar = $subtotal + $ongkosKirim + $biayaApp;

        // Create penjualan record
        $penjualan = Penjualan::create([
            'id_pelanggan' => $pelangganId,
            'id_metode_bayar' => $request->id_metode_bayar,
            'id_jenis_kirim' => $request->id_jenis_kirim,
            'tgl_penjualan' => now(),
            'ongkos_kirim' => $ongkosKirim,
            'biaya_app' => $biayaApp,
            'total_bayar' => $totalBayar,
            'status_order' => 'Menunggu Pembayaran',
            'keterangan_status' => 'Pesanan baru dibuat',
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'catatan' => $request->catatan,
        ]);

        // Create detail penjualan records
        foreach ($keranjangItems as $item) {
            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id,
                'id_obat' => $item->id_obat,
                'jumlah_beli' => $item->jumlah_beli,
                'harga_beli' => $item->obat->harga_jual,
                'subtotal' => $item->subtotal,
            ]);

            // Remove item from cart
            $item->delete();
        }

        // Process Midtrans payment if selected
        $midtransMethod = MetodeBayar::where('metode_pembayaran', 'Midtrans')->first();

        if ($request->id_metode_bayar == $midtransMethod->id) {
            // Proses Midtrans
            return $this->processMidtransPayment($penjualan, $keranjangItems, $ongkosKirim);
        } else {
            // Proses metode bayar biasa
            return redirect()->route('payment.finish', [
                'order_id' => 'ORDER-'.$penjualan->id,
                'status' => 'pending'
            ]);
        }

        // Process Midtrans payment if selected
        if ($request->id_metode_bayar == MetodeBayar::where('metode_pembayaran', 'Midtrans')->first()->id) {
            $paymentData = $this->processMidtransPayment($penjualan, $keranjangItems, $ongkosKirim);
            
            if ($paymentData instanceof \Illuminate\Http\RedirectResponse) {
                return $paymentData;
            }
            
            return response()->json([
                'snapToken' => $snapToken ?? null,
                'redirect' => $request->id_metode_bayar != $this->midtransMethodId 
                    ? route('payment.finish', ['order_id' => 'ORDER-'.$penjualan->id, 'status' => 'pending'])
                    : null
            ]);
        }
            return response()->json([
                'redirect' => route('payment.finish'),
                'order_id' => 'ORDER-' . $penjualan->id,
                'status' => 'pending'
            ]);

        } catch (\Exception $e) {
        return response()->json([
            'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function processMidtransPayment($penjualan, $items, $ongkosKirim = 0)
    {
        $orderId = 'ORDER-' . $penjualan->id . '-' . time();
        
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $penjualan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => optional($penjualan->pelanggan)->nama ?? 'Pelanggan',
                'email' => optional($penjualan->pelanggan)->email ?? 'customer@email.com',
                'phone' => optional($penjualan->pelanggan)->no_telp ?? '',
            ],
            'item_details' => $this->buildItemDetails($items, $ongkosKirim),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Update order ID in database
            $penjualan->update(['order_id' => $orderId]);

            return view('checkout.midtrans', [
                'snapToken' => $snapToken,
                'penjualan' => $penjualan,
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function paymentFinish(Request $request)
    {
        $orderId = $request->order_id;
        $status = $request->status;
        
        // Extract penjualan ID from order ID
        $penjualanId = explode('-', $orderId)[1];
        $penjualan = Penjualan::findOrFail($penjualanId);
        
        // Update status based on payment result
        if ($status === 'success') {
            $penjualan->update([
                'status_order' => 'Paid',
                'keterangan_status' => 'Pembayaran berhasil'
            ]);
        } elseif ($status === 'pending') {
            $penjualan->update([
                'status_order' => 'Menunggu Pembayaran',
                'keterangan_status' => 'Menunggu konfirmasi pembayaran'
            ]);
        } else {
            $penjualan->update([
                'status_order' => 'Gagal',
                'keterangan_status' => 'Pembayaran gagal'
            ]);
        }

        return view('checkout.finish', [
            'status' => $status,
            'penjualan' => $penjualan,
            'order_id' => $orderId
        ]);
    }

    public function handleNotification(Request $request)
    {
        $notif = new Notification();
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $penjualan = Penjualan::where('order_id', $orderId)->first();

        if (!$penjualan) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // Handle transaction status
        switch ($transaction) {
            case 'capture':
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $status = 'Pending';
                        $message = 'Card challenged';
                    } else {
                        $status = 'Paid';
                        $message = 'Payment captured';
                    }
                }
                break;
            case 'settlement':
                $status = 'Paid';
                $message = 'Payment settled';
                break;
            case 'pending':
                $status = 'Pending';
                $message = 'Waiting payment';
                break;
            case 'deny':
                $status = 'Failed';
                $message = 'Payment denied';
                break;
            case 'expire':
                $status = 'Expired';
                $message = 'Payment expired';
                break;
            case 'cancel':
                $status = 'Canceled';
                $message = 'Payment canceled';
                break;
            default:
                $status = 'Pending';
                $message = 'Unknown status';
        }

        $penjualan->update([
            'status_order' => $status,
            'keterangan_status' => $message
        ]);

        return response()->json(['status' => 'success']);
    }

    private function buildItemDetails($items, $ongkir)
    {
        $itemDetails = [];
        
        foreach ($items as $item) {
            $obat = is_array($item) ? Obat::find($item['id_obat']) : $item->obat;
            
            $itemDetails[] = [
                'id' => $obat->id,
                'price' => is_array($item) ? $item['harga'] : $obat->harga_jual,
                'quantity' => is_array($item) ? $item['jumlah'] : $item->jumlah_beli,
                'name' => $obat->nama_obat,
            ];
        }

        // Add shipping cost if exists
        if ($ongkir > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $ongkir,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        return $itemDetails;
    }

    private function calculateTotal($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['jumlah'] * $item['harga'];
        }
        return $total;
    }
}