<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Distributor;
use App\Models\Obat;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('distributor')->latest()->get();
        return view('be.pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $distributors = Distributor::all();
        $obats = Obat::all();
        
        $lastNota = Pembelian::orderBy('id', 'desc')->first();
        $no_nota = 'NOTA-' . date('Ymd') . '-' . sprintf('%03d', ($lastNota ? $lastNota->id + 1 : 1));
        
        return view('be.pembelian.create', compact('distributors', 'obats', 'no_nota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_nota' => 'required|unique:pembelians',
            'tgl_pembelian' => 'required|date',
            'id_distributor' => 'required|exists:distributors,id',
            'id_obat' => 'required|array',
            'id_obat.*' => 'exists:obats,id',
            'jumlah_beli' => 'required|array',
            'jumlah_beli.*' => 'numeric|min:1',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'numeric|min:0',
        ]);

        $total_bayar = 0;
        foreach ($request->obat_id as $key => $obat_id) {
            $subtotal = $request->jumlah[$key] * $request->harga_beli[$key];
            $total_bayar += $subtotal;
        }

        $pembelian = Pembelian::create([
            'no_nota' => $request->no_nota,
            'tgl_pembelian' => $request->tgl_pembelian,
            'id_distributor' => $request->id_distributor,
            'total_bayar' => $total_bayar,
        ]);

        foreach ($request->obat_id as $key => $obat_id) {
            DetailPembelian::create([
                'id_pembelian' => $pembelian->id,
                'id_obat' => $obat_id,
                'jumlah_beli' => $request->jumlah[$key],
                'harga_beli' => $request->harga_beli[$key],
                'subtotal' => $request->jumlah[$key] * $request->harga_beli[$key],
            ]);

            $obat = Obat::find($obat_id);
            $obat->stok += $request->jumlah[$key];
            $obat->save();
        }

        return redirect()->route('pembelian.manage')->with('success', 'Pembelian berhasil disimpan');
    }

   public function show($id)
    {
        $pembelian = Pembelian::with(['distributor', 'detailPembelians.obat'])->findOrFail($id);
        if (!$pembelian->distributor) {
            logger("Missing distributor for pembelian: " . $pembelian->id);
        }
        return view('be.pembelian.show', compact('pembelian'));
    }
    
    public function edit($id)
    {
        $pembelian = Pembelian::with('detailPembelians')->findOrFail($id);
        $distributors = Distributor::all();
        $obats = Obat::all();
        return view('be.pembelian.edit', compact('pembelian', 'distributors', 'obats'));
    }

    public function update(Request $request, $id)
    {
        // Implement update logic here
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        
        // Kembalikan stok obat
        foreach ($pembelian->detailPembelians as $detail) {
            $obat = Obat::find($detail->id_obat);
            $obat->stok -= $detail->jumlah_beli;
            $obat->save();
        }
        
        // Hapus detail pembelian
        $pembelian->detailPembelians()->delete();
        
        // Hapus pembelian
        $pembelian->delete();
        
        return redirect()->route('pembelian.manage')->with('success', 'Pembelian berhasil dihapus');
    }
}