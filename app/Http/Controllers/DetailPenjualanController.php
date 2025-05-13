<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Obat;
use Illuminate\Http\Request;

class DetailPenjualanController extends Controller
{
    public function index()
    {
        $details = DetailPenjualan::with(['penjualan', 'obat'])->get();

        return view('be.detail-penjualan.index', [
            'title' => 'Detail Penjualan',
            'details' => $details,
        ]);
    }

    public function create()
    {
        return view('be.detail-penjualan.create', [
            'title' => 'Tambah Detail Penjualan',
            'penjualan' => Penjualan::all(),
            'obat' => Obat::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan' => 'required|exists:penjualans,id',
            'id_obat' => 'required|exists:obats,id',
            'jumlah_beli' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        DetailPenjualan::create($request->all());

        return redirect()->route('detail-penjualan.index')->with('success', 'Detail penjualan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $detail = DetailPenjualan::with(['penjualan', 'obat'])->findOrFail($id);

        return view('be.detail-penjualan.show', [
            'title' => 'Detail Penjualan',
            'detail' => $detail,
        ]);
    }

    public function edit($id)
    {
        $detail = DetailPenjualan::findOrFail($id);

        return view('be.detail-penjualan.edit', [
            'title' => 'Edit Detail Penjualan',
            'detail' => $detail,
            'penjualan' => Penjualan::all(),
            'obat' => Obat::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $detail = DetailPenjualan::findOrFail($id);

        $request->validate([
            'id_penjualan' => 'required|exists:penjualans,id',
            'id_obat' => 'required|exists:obats,id',
            'jumlah_beli' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $detail->update($request->all());

        return redirect()->route('detail-penjualan.index')->with('success', 'Detail penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $detail = DetailPenjualan::findOrFail($id);
        $detail->delete();

        return redirect()->route('detail-penjualan.index')->with('success', 'Detail penjualan berhasil dihapus.');
    }
}
