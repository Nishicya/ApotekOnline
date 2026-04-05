<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\Pelanggan;
use App\Models\Keranjang;
use App\Models\MetodeBayar;
use App\Models\JenisPengiriman;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class KeranjangController extends Controller
{
    protected $midtransMethod;

    public function __construct()
    {
        $this->midtransMethod = MetodeBayar::where('payment_gateway', 'midtrans')->first();
        
        if ($this->midtransMethod) {
            MidtransConfig::$serverKey = config('midtrans.server_key');
            MidtransConfig::$clientKey = config('midtrans.client_key');
            MidtransConfig::$isProduction = config('midtrans.is_production', false);
            MidtransConfig::$isSanitized = config('midtrans.is_sanitized', true);
            MidtransConfig::$is3ds = config('midtrans.is_3ds', true);
            
            Log::info('Midtrans Config Loaded', [
                'server_key_exists' => !empty(config('midtrans.server_key')),
                'is_production' => MidtransConfig::$isProduction
            ]);
        }
    }

    public function index()
    {
        if (auth('pelanggan')->check()) {
            $cartItems = Keranjang::where('id_pelanggan', auth('pelanggan')->id())
                            ->with('obat')
                            ->get();
            $total = $cartItems->sum('subtotal');
        } else {
            $cart = session()->get('cart', []);
            $total = 0;
            
            $cartItems = collect($cart)->map(function($item) {
                return (object)[
                    'id' => $item['id'],
                    'id_obat' => $item['id'],
                    'jumlah_order' => $item['quantity'],
                    'harga' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'obat' => (object)[
                        'id' => $item['id'],
                        'nama_obat' => $item['name'],
                        'foto1' => $item['photo'],
                        'stok' => $item['stok']
                    ]
                ];
            });
        }
        
        return view('keranjang.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        try {
            if (!auth('pelanggan')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu',
                    'login_required' => true
                ], 401);
            }

            $validated = $request->validate([
                'id_obat' => 'required|exists:obats,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Obat::findOrFail($validated['id_obat']);

            if ($product->stok < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stok
                ], 400);
            }

            $harga = $product->harga_jual;
            
            // Cek apakah produk sudah ada di keranjang
            $existingItem = Keranjang::where('id_pelanggan', auth('pelanggan')->id())
                                    ->where('id_obat', $product->id)
                                    ->first();

            if ($existingItem) {
                // Item sudah ada, tambahkan quantity
                $newQuantity = $existingItem->jumlah_order + $validated['quantity'];
                $newSubtotal = $newQuantity * $harga;
                
                $existingItem->update([
                    'jumlah_order' => $newQuantity,
                    'harga' => $harga,
                    'subtotal' => $newSubtotal
                ]);
            } else {
                // Item baru, create dengan subtotal yang dihitung
                $subtotal = $validated['quantity'] * $harga;
                
                Keranjang::create([
                    'id_pelanggan' => auth('pelanggan')->id(),
                    'id_obat' => $product->id,
                    'jumlah_order' => $validated['quantity'],
                    'harga' => $harga,
                    'subtotal' => $subtotal
                ]);
            }

            // Hitung total item (bukan total quantity)
            $cartCount = Keranjang::where('id_pelanggan', auth('pelanggan')->id())->count();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'cart_count' => $cartCount
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Cari keranjang by ID (bukan obat ID)
        $cartItem = Keranjang::findOrFail($id);

        // Validasi keranjang punya user yang login
        if ($cartItem->id_pelanggan != auth('pelanggan')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        // Cari produk untuk validasi stok
        $product = Obat::findOrFail($cartItem->id_obat);

        if ($product->stok < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stok
            ], 400);
        }

        // Gunakan harga dari obat untuk memastikan akurasi
        $harga = $product->harga_jual;
        $subtotal = $request->quantity * $harga;

        $cartItem->update([
            'jumlah_order' => $request->quantity,
            'harga' => $harga,
            'subtotal' => $subtotal
        ]);

        // Hitung total keranjang
        $totalItems = Keranjang::where('id_pelanggan', auth('pelanggan')->id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui',
            'subtotal' => $subtotal,
            'formatted_subtotal' => 'Rp' . number_format($subtotal, 0, ',', '.'),
            'cart_count' => $totalItems
        ]);
    }

    public function remove(Request $request, $id)
    {
        if (auth('pelanggan')->check()) {
            // Delete keranjang item by keranjang id, not obat id
            $deleted = Keranjang::where('id_pelanggan', auth('pelanggan')->id())
                    ->where('id', $id)
                    ->delete();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => $deleted > 0,
                    'message' => $deleted > 0 ? 'Produk dihapus' : 'Produk tidak ditemukan'
                ]);
            }
        } else {
            $cart = session()->get('cart', []);
            if(isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                
                if ($request->ajax()) {
                    return response()->json(['success' => true]);
                }
            }
        }

        return back()->with('success', 'Produk dihapus');
    }

    public function checkout(Request $request)
    {
        if (!auth('pelanggan')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $selectedItems = $request->input('selected_items', []);
        $cartItems = Keranjang::with('obat')
                    ->where('id_pelanggan', auth('pelanggan')->id())
                    ->when(!empty($selectedItems), function($query) use ($selectedItems) {
                        return $query->whereIn('id', $selectedItems);
                    })
                    ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Keranjang kosong');
        }

        return view('checkout.index', [
            'pelanggan' => auth('pelanggan')->user(),
            'keranjangItems' => $cartItems,
            'metodeBayar' => MetodeBayar::all(),
            'jenisPengiriman' => JenisPengiriman::all(),
            'selectedItems' => $selectedItems
        ]);
    }

    public function processCheckout(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'id_metode_bayar' => 'required|exists:metode_bayars,id',
                'id_jenis_kirim' => 'required|exists:jenis_pengirimans,id',
                'alamat_pengiriman' => 'required|string|max:255',
                'catatan' => 'nullable|string|max:500',
                'selected_items' => 'required|array|min:1',
                'selected_items.*' => 'exists:keranjangs,id,id_pelanggan,'.auth('pelanggan')->id()
            ]);

            Log::info('Checkout Process Started', [
                'id_metode_bayar' => $validated['id_metode_bayar'],
                'midtrans_method_id' => $this->midtransMethod?->id
            ]);

            $cartItems = Keranjang::with('obat')
                        ->where('id_pelanggan', auth('pelanggan')->id())
                        ->whereIn('id', $validated['selected_items'])
                        ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Tidak ada item yang dipilih');
            }

            // Calculate totals
            $biayaApp = 2000;
            $subtotal = $cartItems->sum('subtotal');
            $shipping = JenisPengiriman::findOrFail($validated['id_jenis_kirim'])->harga;
            $total = $subtotal + $shipping + $biayaApp;

            // Create order
            $order = Penjualan::create([
                'id_pelanggan' => auth('pelanggan')->id(),
                'id_metode_bayar' => $validated['id_metode_bayar'],
                'id_jenis_kirim' => $validated['id_jenis_kirim'],
                'tgl_penjualan' => now(),
                'ongkos_kirim' => $shipping,
                'biaya_app' => $biayaApp,
                'total_bayar' => $total,
                'status_order' => 'Menunggu Pembayaran',
                'keterangan_status' => 'Menunggu Pembayaran',
                'alamat_pengiriman' => $validated['alamat_pengiriman'],
                'catatan' => $validated['catatan']
            ]);

            Log::info('Order Created', ['order_id' => $order->id]);

            // Order Items
            foreach ($cartItems as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $order->id,
                    'id_obat' => $item->id_obat,
                    'jumlah_beli' => $item->jumlah_order,
                    'harga_beli' => $item->harga,
                    'subtotal' => $item->subtotal
                ]);

                $item->obat->decrement('stok', $item->jumlah_order);
                $item->delete();
            }

            // Create Pengiriman record
            $jenisPengiriman = JenisPengiriman::findOrFail($validated['id_jenis_kirim']);
            Pengiriman::create([
                'id_penjualan' => $order->id,
                'no_invoice' => 'INV-' . $order->id . '-' . time(),
                'status_kirim' => 'Menunggu Konfirmasi',
                'nama_kurir' => $jenisPengiriman->nama_dispatch ?? 'Belum ditentukan',
                'keterangan' => 'Menunggu Konfirmasi dari Apoteker'
            ]);

            // Midtrans - Check for payment method
            Log::info('Checking payment method', [
                'method_id' => $validated['id_metode_bayar'],
                'is_midtrans' => $this->isMidtransPayment($validated['id_metode_bayar'])
            ]);

            if ($this->isMidtransPayment($validated['id_metode_bayar'])) {
                Log::info('Processing Midtrans Payment');
                try {
                    $payment = $this->processMidtransPayment($order, $cartItems, $shipping, $biayaApp);
                    DB::commit();
                    Log::info('Midtrans Payment Processed Successfully', ['order_id' => $payment['order_id']]);
                    return response()->json([
                        'success' => true,
                        'snapToken' => $payment['snapToken'],
                        'order_id' => $payment['order_id']
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Midtrans Payment Failed: ' . $e->getMessage());
                    throw $e;
                }
            }

            // Non-Midtrans payment - don't auto redirect
            DB::commit();
            Log::info('Non-Midtrans Payment - Order created successfully');
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.',
                'redirect' => route('fe.pesanan.show', $order->id)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function isMidtransPayment($methodId)
    {
       return $this->midtransMethod && $methodId == $this->midtransMethod->id;
    }

    protected function processMidtransPayment($order, $items, $shippingCost, $biayaApp)
    {
        // ✅ Validasi config Midtrans
        if (empty(config('midtrans.server_key'))) {
            throw new \Exception('Konfigurasi Midtrans belum lengkap. Hubungi admin.');
        }

        $customer = auth('pelanggan')->user();
        $orderId = 'ORDER-'.$order->id.'-'.time(); // ✅ Simpan di variable

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $order->total_bayar // ✅ Cast ke integer
            ],
            'customer_details' => [
                'first_name' => substr($customer->nama, 0, 50),
                'email' => substr($customer->email, 0, 50),
                'phone' => substr($customer->no_telp ?? '08123456789', 0, 20),
            ],
            'item_details' => []
        ];

        foreach ($items as $item) {
            $params['item_details'][] = [
                'id' => (string) $item->id_obat, // ✅ Cast ke string
                'price' => (int) $item->harga, // ✅ Cast ke integer
                'quantity' => (int) $item->jumlah_order, // ✅ Cast ke integer
                'name' => substr($item->obat->nama_obat, 0, 50) // ✅ Batasi 50 karakter
            ];
        }

        if ($shippingCost > 0) {
            $params['item_details'][] = [
                'id' => 'SHIPPING',
                'price' => (int) $shippingCost,
                'quantity' => 1,
                'name' => 'Ongkir'
            ];
        }

        $params['item_details'][] = [
            'id' => 'APPFEE',
            'price' => (int) $biayaApp,
            'quantity' => 1,
            'name' => 'Biaya Aplikasi'
        ];

        try {
            Log::info('Midtrans Request Params', $params);
            
            $snapToken = Snap::getSnapToken($params);
            
            Log::info('Midtrans Snap Token Generated', ['token' => $snapToken]);

            // ✅ PERBAIKAN: Simpan midtrans_order_id ke field yang benar
            // Asumsikan Anda punya field 'midtrans_order_id' di tabel penjualan
            // Jika belum ada, buat migration untuk menambahkannya
            
            if (in_array('midtrans_order_id', $order->getFillable())) {
                $order->update(['midtrans_order_id' => $orderId]);
            }

            return [
                'snapToken' => $snapToken,
                'order_id' => $orderId,
                'id_penjualan' => $order->id // ✅ ID penjualan dari database
            ];
            
        } catch (\Exception $e) {
            Log::error('Midtrans error: '.$e->getMessage());
            throw new \Exception('Gagal memproses pembayaran: '.$e->getMessage());
        }
    }


    public function getCartItems()
    {
        try {
            if (!auth('pelanggan')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'items' => []
                ], 401);
            }

            $cartItems = Keranjang::where('id_pelanggan', auth('pelanggan')->id())
                        ->with('obat')
                        ->latest()
                        ->get();

            return response()->json([
                'success' => true,
                'items' => $cartItems,
                'count' => $cartItems->count(),
                'total' => $cartItems->sum('subtotal')
            ]);
        } catch (\Exception $e) {
            Log::error('Get cart items error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching cart items',
                'items' => []
            ], 500);
        }
    }

}