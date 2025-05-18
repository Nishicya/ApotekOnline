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
            MidtransConfig::$serverKey = config('midtrans.serverKey');
            MidtransConfig::$isProduction = config('midtrans.isProduction');
            MidtransConfig::$isSanitized = true;
            MidtransConfig::$is3ds = true;
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
        if (!auth('pelanggan')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu',
                'login_required' => true
            ], 401);
        }

        $request->validate([
            'id_obat' => 'required|exists:obats,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Obat::findOrFail($request->id_obat);

        if ($product->stok < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stok
            ], 400);
        }

        $cartItem = Keranjang::updateOrCreate(
            [
                'id_pelanggan' => auth('pelanggan')->id(),
                'id_obat' => $product->id
            ],
            [
                'jumlah_order' => DB::raw('jumlah_order + ' . $request->quantity),
                'harga' => $product->harga_jual,
                'subtotal' => DB::raw('(jumlah_order + ' . $request->quantity . ') * ' . $product->harga_jual)
            ]
        );

        $cartCount = Keranjang::where('id_pelanggan', auth('pelanggan')->id())->sum('jumlah_order');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Obat::findOrFail($id);

        if ($product->stok < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        if (auth('pelanggan')->check()) {
            $cartItem = Keranjang::where('id_pelanggan', auth('pelanggan')->id())
                            ->where('id_obat', $id)
                            ->firstOrFail();

            $cartItem->update([
                'jumlah_order' => $request->quantity,
                'subtotal' => $request->quantity * $cartItem->harga
            ]);
        } else {
            $cart = session()->get('cart', []);
            if(isset($cart[$id])) {
                $cart[$id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
        }

        return back()->with('success', 'Keranjang diperbarui');
    }

    public function remove(Request $request, $id)
    {
        if (auth('pelanggan')->check()) {
            Keranjang::where('id_pelanggan', auth('pelanggan')->id())
                    ->where('id_obat', $id)
                    ->delete();
        } else {
            $cart = session()->get('cart', []);
            if(isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
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
                'status_order' => 'Menunggu Konfirmasi',
                'alamat_pengiriman' => $validated['alamat_pengiriman'],
                'catatan' => $validated['catatan']
            ]);

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

            // Midtrans
            if ($this->isMidtransPayment($validated['id_metode_bayar'])) {
                $payment = $this->processMidtransPayment($order, $cartItems, $shipping, $biayaApp);
                DB::commit();
                return response()->json($payment);
            }

            DB::commit();
            return response()->json([
                'redirect' => route('orders.show', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: '.$e->getMessage());
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function isMidtransPayment($methodId)
    {
        return $this->midtransMethod && $this->midtransMethod->id == $methodId;
    }

    protected function processMidtransPayment($order, $items, $shippingCost, $biayaApp)
    {
        $customer = auth('pelanggan')->user();

        $params = [
            'transaction_details' => [
                'id_penjualan' => 'ORDER-'.$order->id.'-'.time(),
                'gross_amount' => $order->total_bayar
            ],
            'customer_details' => [
                'first_name' => $customer->nama,
                'email' => $customer->email,
                'phone' => $customer->no_telp
            ],
            'item_details' => []
        ];

        foreach ($items as $item) {
            $params['item_details'][] = [
                'id' => $item->id_obat,
                'price' => $item->harga,
                'quantity' => $item->jumlah_order,
                'name' => $item->obat->nama_obat
            ];
        }

        if ($shippingCost > 0) {
            $params['item_details'][] = [
                'id' => 'SHIPPING',
                'price' => $shippingCost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim'
            ];
        }

        // Tambah Biaya Aplikasi
        $params['item_details'][] = [
            'id' => 'APPFEE',
            'price' => $biayaApp,
            'quantity' => 1,
            'name' => 'Biaya Aplikasi'
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['id_penjualan' => $params['transaction_details']['id_penjualan']]);

            return [
                'snapToken' => $snapToken,
                'id_penjualan' => $params['transaction_details']['id_penjualan']
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans error: '.$e->getMessage());
            throw new \Exception('Gagal memproses pembayaran');
        }
    }

}