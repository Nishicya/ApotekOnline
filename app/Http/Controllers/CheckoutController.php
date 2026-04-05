<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\MetodeBayar;
use App\Models\JenisPengiriman;
use App\Models\Keranjang;
use App\Models\Obat;
use App\Models\Pengiriman;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $midtransMethod;

    private function validateMidtransConfig()
    {
        if (empty(config('midtrans.serverKey'))) {
            throw new \Exception('Konfigurasi Midtrans belum lengkap');
        }
    }

    public function __construct()
    {
        // $this->middleware('auth:pelanggan');
        
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
        Log::info('Checkout process initiated', ['request' => $request->all()]);

        try {
            $validated = $request->validate([
                'id_metode_bayar' => [
                    'required',
                    'exists:metode_bayars,id',
                    function ($attribute, $value, $fail) {
                        if ($value == $this->midtransMethod?->id && empty(config('midtrans.serverKey'))) {
                            $fail('Pembayaran Midtrans sedang tidak tersedia');
                        }
                    }
                ],
                'id_jenis_kirim' => 'required|exists:jenis_pengirimans,id',
                'alamat_pengiriman' => 'required|string|max:255',
                'catatan' => 'nullable|string|max:500',
                'selected_items' => 'required|array|min:1',
                'selected_items.*' => 'exists:keranjangs,id,id_pelanggan,'.auth()->id()
            ], [
                'id_metode_bayar.required' => 'Pilih metode pembayaran',
                'id_jenis_kirim.required' => 'Pilih jenis pengiriman',
                'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi',
                'selected_items.required' => 'Pilih minimal 1 produk untuk checkout'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Checkout validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $pelangganId = auth()->id();
            $selectedItems = $request->input('selected_items', []);
            
            $keranjangItems = Keranjang::with('obat')
                ->where('id_pelanggan', $pelangganId)
                ->whereIn('id', $selectedItems)
                ->get();

            if ($keranjangItems->isEmpty()) {
                throw new \Exception('Tidak ada item yang dipilih untuk checkout');
            }

            // Calculate totals
            $subtotal = $keranjangItems->sum('subtotal');
            $jenisPengiriman = JenisPengiriman::findOrFail($request->id_jenis_kirim);
            $ongkosKirim = $jenisPengiriman->harga ?? 0;
            $biayaApp = 0;
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
                'status_order' => 'Menunggu Pembayaran',
                'keterangan_status' => 'Pesanan baru dibuat',
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'catatan' => $request->catatan,
            ]);

            // Create order items
            foreach ($keranjangItems as $item) {
                // Check stock availability
                if ($item->obat->stok < $item->jumlah_beli) {
                    throw new \Exception('Stok '.$item->obat->nama_obat.' tidak mencukupi');
                }

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

            // Create shipping data
            $pengiriman = Pengiriman::create([
                'id_penjualan' => $penjualan->id,
                'no_invoice' => 'INV-' . $penjualan->id . '-' . time(),
                'status_kirim' => 'Menunggu Konfirmasi',
                'nama_kurir' => $jenisPengiriman->nama_dispatch ?? 'Belum ditentukan',
                'telpon_kurir' => '',
                'keterangan' => 'Menunggu konfirmasi dari apoteker'
            ]);

            // Process payment
            if ($this->isMidtransPayment($request->id_metode_bayar)) {
                $midtransResponse = $this->processMidtransPayment($penjualan, $keranjangItems, $ongkosKirim);
                
                if (isset($midtransResponse['snapToken'])) {
                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'snapToken' => $midtransResponse['snapToken'],
                        'id_penjualan' => $penjualan->id,
                        'message' => 'Redirect ke halaman pembayaran'
                    ]);
                } else {
                    throw new \Exception('Gagal memproses pembayaran Midtrans');
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'redirect' => route('payment.finish', [
                    'id_penjualan' => $penjualan->id, 
                    'status' => 'pending'
                ]),
                'message' => 'Pesanan berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isMidtransPayment($methodId)
    {
        return $this->midtransMethod && $methodId == $this->midtransMethod->id;
    }

    private function processMidtransPayment($penjualan, $items, $shippingCost)
    {
        $this->validateMidtransConfig();

        try {
            $orderId = 'ORDER-' . $penjualan->id . '-' . time();
            $customer = $penjualan->pelanggan;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $penjualan->total_bayar,
                ],
                'customer_details' => [
                    'first_name' => substr($customer->nama ?? 'Customer', 0, 50),
                    'email' => substr($customer->email ?? 'customer@example.com', 0, 50),
                    'phone' => substr($customer->no_telp ?? '08123456789', 0, 20),
                    'address' => substr($penjualan->alamat_pengiriman, 0, 100),
                ],
                'enabled_payments' => ['credit_card', 'gopay', 'shopeepay'],
                'callbacks' => [
                    'finish' => route('payment.finish')
                ]
            ];

            // Tambahkan item details
            $params['item_details'] = $this->buildItemDetails($items, $shippingCost);

            // Generate token
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan order_id ke database
            $penjualan->update(['midtrans_order_id' => $orderId]);

            return [
                'snapToken' => $snapToken,
                'order_id' => $orderId,
                'redirect_url' => config('midtrans.isProduction') 
                    ? "https://app.midtrans.com/snap/v2/vtweb/$snapToken"
                    : "https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken"
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Error: '.$e->getMessage());
            throw new \Exception('Gagal menghubungkan ke payment gateway: '.$e->getMessage());
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
                'category' => 'Obat'
            ];
        }

        if ($shippingCost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $shippingCost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
                'category' => 'Pengiriman'
            ];
        }

        return $itemDetails;
    }

    public function paymentFinish(Request $request)
    {
        try {
            $status_code = $request->query('status_code');
            $transaction_status = $request->query('transaction_status');
            
            Log::info('Payment Finish Accessed', [
                'status_code' => $status_code,
                'transaction_status' => $transaction_status,
                'url_params' => $request->query()
            ]);

            // Map status
            $statusMap = [
                '200' => 'settlement',
                '201' => 'pending',
                '202' => 'deny',
                '407' => 'expire',
                '408' => 'cancel'
            ];

            $mappedStatus = $statusMap[$status_code] ?? $transaction_status ?? 'pending';
            
            // Get latest order for this customer
            $penjualan = Penjualan::where('id_pelanggan', auth('pelanggan')->id() ?? auth()->id())
                ->latest()
                ->first();

            if (!$penjualan) {
                return view('checkout.finish', [
                    'status' => 'error',
                    'message' => 'Pesanan tidak ditemukan',
                    'penjualan' => null,
                    'keranjangItems' => collect([])
                ]);
            }

            // Update order status based on payment result
            $statusDataMap = [
                'settlement' => ['status' => 'Diproses', 'message' => 'Pembayaran berhasil diterima'],
                'capture' => ['status' => 'Diproses', 'message' => 'Pembayaran berhasil diterima'],
                'pending' => ['status' => 'Menunggu Pembayaran', 'message' => 'Menunggu konfirmasi pembayaran'],
                'deny' => ['status' => 'Dibatalkan', 'message' => 'Pembayaran ditolak'],
                'expire' => ['status' => 'Dibatalkan', 'message' => 'Pembayaran kadaluarsa'],
                'cancel' => ['status' => 'Dibatalkan', 'message' => 'Pesanan dibatalkan'],
                'error' => ['status' => 'Error', 'message' => 'Terjadi kesalahan dalam pembayaran']
            ];

            $statusData = $statusDataMap[$mappedStatus] ?? $statusDataMap['pending'];
            
            // Only update if status is different (to avoid duplicate updates)
            if ($penjualan->status_order !== $statusData['status']) {
                $penjualan->update([
                    'status_order' => $statusData['status'],
                    'keterangan_status' => $statusData['message']
                ]);

                // Keep pengiriman as 'Menunggu Konfirmasi' until admin confirms it
                // Admin will confirm and assign kurir, then status becomes 'Sedang Dikirim'
            }

            // Get order items for display
            $keranjangItems = DetailPenjualan::where('id_penjualan', $penjualan->id)
                ->with('obat')
                ->get();

            return view('checkout.finish', [
                'status' => $mappedStatus,
                'penjualan' => $penjualan,
                'keranjangItems' => $keranjangItems,
                'message' => $statusData['message']
            ]);

        } catch (\Exception $e) {
            Log::error('Payment finish error: ' . $e->getMessage());
            return view('checkout.finish', [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage(),
                'penjualan' => null,
                'keranjangItems' => collect([])
            ]);
        }
    }

    public function handleNotification(Request $request)
    {
        $notif = new Notification();
        
        try {
            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;

            $penjualan = Penjualan::where('id_penjualan', $orderId)->first();
            if (!$penjualan) {
                throw new \Exception('Order not found: ' . $orderId);
            }

            $statusMap = [
                'capture' => 'Diproses',
                'settlement' => 'Diproses',
                'pending' => 'Menunggu Pembayaran',
                'deny' => 'Dibatalkan',
                'expire' => 'Dibatalkan',
                'cancel' => 'Dibatalkan'
            ];

            $status = $statusMap[$transaction] ?? 'Menunggu Pembayaran';

            DB::transaction(function() use ($penjualan, $status, $transaction) {
                $penjualan->update([
                    'status_order' => $status,
                    'keterangan_status' => 'Status Midtrans: ' . $transaction
                ]);

                if (in_array($status, ['Diproses'])) {
                    $penjualan->pengiriman()->update([
                        'status_kirim' => 'Diproses',
                        'keterangan' => 'Pesanan sedang diproses'
                    ]);
                }
            });

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}