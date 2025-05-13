<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Obat;
use App\Models\MetodeBayar;
use App\Models\JenisPengiriman;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // Contoh method checkout untuk menambahkan penjualan
public function checkout(Request $request)
    {
        $request->validate([
            'metode_bayar' => 'required|exists:metode_bayars,id',
            'alamat_pengiriman' => 'required|string',
            // Validasi lainnya
        ]);

        // Buat data penjualan baru
        $penjualan = Penjualan::create([
            'id_pelanggan' => Auth::guard('pelanggan')->id(),
            'id_metode_bayar' => $request->metode_bayar,
            'total_bayar' => $this->calculateTotal($request->obat), // Hitung total bayar
            'status_order' => 'Pending', // Status awal penjualan
            'alamat_pengiriman' => $request->alamat_pengiriman,
        ]);

        // Tambahkan detail penjualan (obat yang dibeli)
        foreach ($request->obat as $item) {
            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id,
                'id_obat' => $item['id_obat'],
                'jumlah_beli' => $item['jumlah'],
                'harga_beli' => $item['harga'],
                'subtotal' => $item['jumlah'] * $item['harga'],
            ]);
        }

        return redirect()->route('penjualan.manage')->with('success', 'Penjualan berhasil dibuat.');
    }

}
