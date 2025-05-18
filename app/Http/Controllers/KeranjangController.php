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
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        if (auth('pelanggan')->check()) {
            // Jika pelanggan login, ambil dari database
            $cartItem = Keranjang::where('id_pelanggan', auth('pelanggan')->id())->with('obat')->get();
            $total = $cartItem->sum(function($item) {
                return $item->harga * $item->jumlah_order;
            });
        } else {
            // Jika guest, ambil dari session
            $cart = session()->get('cart', []);
            $total = 0;
            
            $cartItem = collect($cart)->map(function($item) {
                return (object)[
                    'id' => $item['id'],
                    'id_obat' => $item['id'],
                    'jumlah_order' => $item['quantity'],
                    'harga' => $item['price'],
                    'obat' => (object)[
                        'id' => $item['id'],
                        'nama_obat' => $item['name'],
                        'foto1' => $item['photo'],
                        'stok' => $item['stok']
                    ]
                ];
            });
        }
        
        return view('keranjang.index', compact('cartItem', 'total'));
    }

    public function add(Request $request)
    {
        if (!auth('pelanggan')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang',
                'login_required' => true
            ], 401);
        }

        $request->validate([
            'id_obat' => 'required|exists:obats,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Obat::findOrFail($request->id_obat);

        // Cek stok tersedia
        if ($product->stok < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stok,
                'login_required' => false
            ], 400);
        }

        // Check if item already exists in cart
        $cartItem = Keranjang::where('id_pelanggan', auth('pelanggan')->id())
                            ->where('id_obat', $product->id)
                            ->first();

        if ($cartItem) {
            // Update menggunakan harga_jual
            $cartItem->jumlah_order += $request->quantity;
            $cartItem->harga = $product->harga_jual; // Tambahkan ini
            $cartItem->subtotal = $cartItem->jumlah_order * $product->harga_jual; // Diubah
            $cartItem->save();
        } else {
            // Create menggunakan harga_jual
            $cartItem = Keranjang::create([
                'id_pelanggan' => auth('pelanggan')->id(),
                'id_obat' => $product->id,
                'jumlah_order' => $request->quantity,
                'harga' => $product->harga_jual, // Diubah
                'subtotal' => $request->quantity * $product->harga_jual // Diubah
            ]);
        }

        $cartCount = Keranjang::where('id_pelanggan', auth('pelanggan')->id())->sum('jumlah_order');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => $cartCount,
            'login_required' => false
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Obat::findOrFail($id);

        if ($product->stok < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stok);
        }

        if (Auth::check()) {
            $cartItem = Keranjang::where('id_pelanggan', Auth::id())
                            ->where('id_obat', $id)
                            ->first();

            if ($cartItem) {
                $cartItem->jumlah_order = $request->quantity;
                $cartItem->harga = $product->harga_jual;
                $cartItem->subtotal = $cartItem->jumlah_order * $product->harga_jual;
                $cartItem->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if(isset($cart[$id])) {
                $cart[$id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
        }

        return back()->with('success', 'Keranjang berhasil diperbarui');
    }

    public function checkout(Request $request)
    {
        $pelanggan = null;
        if (session('loginId')) {
            $pelanggan = Pelanggan::find(session('loginId'));
            $keranjangItems = Keranjang::with('obat')
                ->where('id_pelanggan', session('loginId'))
                ->get();
        } else {
            return redirect()->route('signin')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get selected items from request
        $selectedItems = $request->input('selected_items', []);

        // Filter only selected items if any
        if (!empty($selectedItems)) {
            $keranjangItems = $keranjangItems->whereIn('id', $selectedItems);
        }

        if ($keranjangItems->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Tidak ada item yang dipilih untuk checkout');
        }

        $metodeBayar = MetodeBayar::all();
        $jenisPengiriman = JenisPengiriman::all();

        return view('checkout.index', [
            'title' => 'Checkout',
            'pelanggan' => $pelanggan,
            'keranjangItems' => $keranjangItems,
            'metodeBayar' => $metodeBayar,
            'jenisPengiriman' => $jenisPengiriman,
            'selectedItems' => $selectedItems
        ]);
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
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
            'status_order' => 'Menunggu Konfirmasi',
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
        }

        return redirect()->route('keranjang')->with('success', 'Pesanan berhasil dibuat dengan nomor #' . $penjualan->id);
    }

    public function remove($id)
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

        // Return JSON response untuk AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang'
            ]);
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }
}
