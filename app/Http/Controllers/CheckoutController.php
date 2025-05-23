<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\MetodeBayar;
use App\Models\JenisPengiriman;
use App\Models\Keranjang;
use App\Models\Obat;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $midtransMethod;

    public function __construct()
    {
        $this->midtransMethod = MetodeBayar::where('payment_gateway', 'midtrans')->first();
        
        if ($this->midtransMethod) {
            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = config('midtrans.isProduction');
            Config::$isSanitized = true;
            Config::$is3ds = true;
        }
    }

    public function processCheckout(Request $request)
    {
        \Log::debug('Checkout request data:', $request->all());

        try {
            $validated = $request->validate([
                'id_metode_bayar' => 'required|exists:metode_bayars,id',
                'id_jenis_kirim' => 'required|exists:jenis_pengirimans,id',
                'alamat_pengiriman' => 'required|string|max:255',
                'catatan' => 'nullable|string|max:500',
                'selected_items' => 'required|array|min:1',
                'selected_items.*' => 'exists:keranjangs,id,id_pelanggan,'.session('loginId')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Checkout validation failed:', $e->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        DB::beginTransaction();

        try {
            $pelangganId = session('loginId');
            $selectedItems = $request->input('selected_items', []);
            
            $keranjangItems = Keranjang::with('obat')
                ->where('id_pelanggan', $pelangganId)
                ->whereIn('id', $selectedItems)
                ->get();

            if ($keranjangItems->isEmpty()) {
                return redirect()->route('keranjang')->with('error', 'No items selected for checkout');
            }

            // Calculate totals
            $subtotal = $keranjangItems->sum('subtotal');
            $jenisPengiriman = JenisPengiriman::findOrFail($request->id_jenis_kirim);
            $ongkosKirim = $jenisPengiriman->harga ?? 0; // Pastikan ada nilai default
            $biayaApp = 0; // Default value jika tidak ada
            $totalBayar = $subtotal + $ongkosKirim + $biayaApp;

            // Create transaction
            $penjualan = Penjualan::create([
                'id_pelanggan' => $pelangganId,
                'id_metode_bayar' => $request->id_metode_bayar,
                'id_jenis_kirim' => $request->id_jenis_kirim,
                'tgl_penjualan' => now(),
                'ongkos_kirim' => $ongkosKirim,
                'biaya_app' => $biayaApp,
                'total_bayar' => $totalBayar,
                'status_order' => 'Menunggu Konfirmasi',
                'keterangan_status' => 'Pesanan baru dibuat',
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'catatan' => $request->catatan,
            ]);

            // Create order items
            foreach ($keranjangItems as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_obat' => $item->id_obat,
                    'jumlah_beli' => $item->jumlah_beli,
                    'harga_beli' => $item->obat->harga_jual,
                    'subtotal' => $item->subtotal,
                ]);

                // Update stock
                $item->obat->decrement('stok', $item->jumlah_beli);
                
                // Remove from cart
                $item->delete();
            }

            // Create initial shipping data
            $pengiriman = Pengiriman::create([
                'id_penjualan' => $penjualan->id,
                'no_invoice' => 'INV-' . $penjualan->id . '-' . time(),
                'status_kirim' => 'Menunggu Pembayaran',
                'nama_kurir' => $jenisPengiriman->nama_dispatch ?? 'Belum ditentukan',
                'telpon_kurir' => '',
                'keterangan' => 'Menunggu pembayaran dan konfirmasi'
            ]);

            // Process payment
            if ($this->isMidtransPayment($request->id_metode_bayar)) {
                $midtransResponse = $this->processMidtransPayment($penjualan, $keranjangItems, $ongkosKirim);
                
                if (isset($midtransResponse['snapToken'])) {
                    DB::commit();
                    return response()->json([
                        'snapToken' => $midtransResponse['snapToken'],
                        'id_penjualan' => $midtransResponse['id_penjualan']
                    ]);
                } else {
                    throw new \Exception('Gagal memproses pembayaran Midtrans');
                }
            }

            DB::commit();
            return response()->json([
                'redirect' => route('payment.finish', ['id_penjualan' => $penjualan->id, 'status' => 'pending'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isMidtransPayment($methodId)
    {
        return $this->midtransMethod && $methodId == $this->midtransMethod->id;
    }

    private function processMidtransPayment($penjualan, $items, $shippingCost)
    {
        $orderId = 'ORDER-' . $penjualan->id . '-' . time();
        
        $params = [
            'transaction_details' => [
                'id_penjualan' => $orderId,
                'gross_amount' => $penjualan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => optional($penjualan->pelanggan)->nama ?? 'Customer',
                'email' => optional($penjualan->pelanggan)->email ?? 'customer@example.com',
                'phone' => optional($penjualan->pelanggan)->no_telp ?? '',
            ],
            'item_details' => $this->buildItemDetails($items, $shippingCost),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Update order with Midtrans reference
            $penjualan->update(['id_penjualan' => $orderId]);

            return view('checkout.midtrans', [
                'snapToken' => $snapToken,
                'id_penjualan' => $orderId,
                'penjualan' => $penjualan
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    private function buildItemDetails($items, $shippingCost)
    {
        $itemDetails = [];
        
        foreach ($items as $item) {
            $obat = $item->obat ?? Obat::find($item['id_obat']);
            
            $itemDetails[] = [
                'id' => $obat->id,
                'price' => $obat->harga_jual,
                'quantity' => $item->jumlah_beli ?? $item['jumlah'],
                'name' => $obat->nama_obat,
            ];
        }

        // Add shipping as item
        if ($shippingCost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $shippingCost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        return $itemDetails;
    }

    public function paymentFinish(Request $request)
    {
        $orderId = $request->id_penjualan;
        $status = $request->status;

        // Ambil id numerik dari format ORDER-<id>-<timestamp>
        $numericId = null;
        if (preg_match('/ORDER-(\d+)-/', $orderId, $matches)) {
            $numericId = $matches[1];
        }

        // Cari penjualan berdasarkan id_penjualan atau id
        $penjualan = Penjualan::where('id_penjualan', $orderId)
            ->when($numericId, function($query) use ($numericId) {
                $query->orWhere('id', $numericId);
            })
            ->firstOrFail();

        $statusMap = [
            'success' => ['status' => 'paid', 'message' => 'Pembayaran berhasil'],
            'pending' => ['status' => 'pending', 'message' => 'Menunggu pembayaran'],
            'failed' => ['status' => 'failed', 'message' => 'Pembayaran gagal']
        ];

        $statusData = $statusMap[$status] ?? $statusMap['pending'];
        
        $penjualan->update([
            'status_order' => $statusData['status'],
            'keterangan_status' => $statusData['message']
        ]);

        return view('checkout.finish', [
            'status' => $status,
            'penjualan' => $penjualan,
            'id_penjualan' => $orderId
        ]);
    }

    public function handleNotification(Request $request)
    {
        $notif = new Notification();
        
        $transaction = $notif->transaction_status;
        $orderId = $notif->id_penjualan;

        $penjualan = Penjualan::where('id_penjualan', $orderId)->first();
        if (!$penjualan) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $statusMap = [
            'capture' => 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'canceled'
        ];

        $penjualan->update([
            'status_order' => $statusMap[$transaction] ?? 'pending',
            'keterangan_status' => 'Status Midtrans: ' . $transaction
        ]);
        

        return response()->json(['status' => 'success']);
    }
}