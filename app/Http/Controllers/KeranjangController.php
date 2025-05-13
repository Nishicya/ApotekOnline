<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;

class KeranjangController extends Controller
{
    public function index()
    {
        // Mengambil data keranjang dari session atau database
        $keranjang = session('keranjang', []);
        return view('keranjang.index', compact('keranjang'));
    }

    public function checkout(Request $request)
    {
        // Proses checkout dan membuat penjualan
        $penjualan = Penjualan::create([
            'id_pelanggan' => Auth::guard('pelanggan')->id(),
            'total_bayar' => $this->calculateTotal($request->keranjang),
            // Lainnya...
        ]);

        // Simpan detail penjualan
        foreach ($request->keranjang as $item) {
            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id,
                'id_obat' => $item['id'],
                'jumlah_beli' => $item['jumlah'],
                'harga_beli' => $item['harga'],
                'subtotal' => $item['jumlah'] * $item['harga'],
            ]);
        }

        // Kosongkan keranjang
        session()->forget('keranjang');

        return redirect()->route('penjualan.manage')->with('success', 'Checkout berhasil!');
    }

    private function calculateTotal($keranjang)
    {
        $total = 0;
        foreach ($keranjang as $item) {
            $total += $item['jumlah'] * $item['harga'];
        }
        return $total;
    }
}
