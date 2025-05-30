<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PengirimanController extends Controller
{ 
    public function index()
    {
        $pengiriman = Pengiriman::with('penjualan')->latest()->get();

        return view('be.pengiriman.index', [
            'title' => 'Pengiriman Management',
            'pengiriman' => $pengiriman,
        ]);
    }

    public function create()
    {
        $penjualan = Penjualan::whereDoesntHave('pengiriman')->get();
        
        return view('be.pengiriman.create', [
            'title' => 'Tambah Data Pengiriman',
            'penjualan' => $penjualan, // perbaiki key agar konsisten dengan view
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan' => 'required|exists:penjualans,id|unique:pengirimans,id_penjualan', // perbaiki nama tabel
            'no_invoice' => 'required|unique:pengirimans,no_invoice',
            'tgl_kirim' => 'required|date',
            'tgl_tiba' => 'nullable|date|after_or_equal:tgl_kirim',
            'status_kirim' => 'required|in:Sedang Dikirim,Tiba Di Tujuan',
            'nama_kurir' => 'required|string|max:30',
            'telpon_kurir' => 'required|string|max:15',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->except('bukti_foto');

        // Handle file upload
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $filename = 'delivery-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pengiriman', $filename, 'public');
            $data['bukti_foto'] = $path;
        }

        Pengiriman::create($data);

        return redirect()->route('pengiriman.manage')->with('success', 'Data pengiriman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        
        $penjualan = Penjualan::whereDoesntHave('pengiriman')
                        ->orWhere('id', $pengiriman->id_penjualan)
                        ->get();

        return view('be.pengiriman.edit', [
            'title' => 'Edit Data Pengiriman',
            'pengiriman' => $pengiriman,
            'penjualan' => $penjualan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $request->validate([
            'id_penjualan' => 'required|exists:penjualans,id|unique:pengirimans,id_penjualan,'.$id, // perbaiki nama tabel
            'no_invoice' => 'required|unique:pengirimans,no_invoice,'.$id,
            'tgl_kirim' => 'required|date',
            'tgl_tiba' => 'nullable|date|after_or_equal:tgl_kirim',
            'status_kirim' => 'required|in:Sedang Dikirim,Tiba Di Tujuan',
            'nama_kurir' => 'required|string|max:30',
            'telpon_kurir' => 'required|string|max:15',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->except('bukti_foto');

        // Handle file upload
        if ($request->hasFile('bukti_foto')) {
            // Delete old file if exists
            if ($pengiriman->bukti_foto) {
                Storage::disk('public')->delete($pengiriman->bukti_foto);
            }
            
            $file = $request->file('bukti_foto');
            $filename = 'delivery-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('pengiriman', $filename, 'public');
            $data['bukti_foto'] = $path;
        }

        $pengiriman->update($data);

        return redirect()->route('pengiriman.manage')->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        
        // Delete associated file
        if ($pengiriman->bukti_foto) {
            Storage::disk('public')->delete($pengiriman->bukti_foto);
        }
        
        $pengiriman->delete();

        return redirect()->route('pengiriman.manage')->with('success', 'Data pengiriman berhasil dihapus.');
    }

    public function konfirmasiKurir($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        $penjualan = $pengiriman->penjualan;
        if ($penjualan && strtolower($penjualan->status_order) == 'menunggu kurir') {
            $penjualan->status_order = 'Diproses';
            $penjualan->save();
            return redirect()->route('pengiriman.manage')->with('success', 'Pesanan berhasil dikonfirmasi ke kurir dan status penjualan menjadi Diproses.');
        }
        return redirect()->route('pengiriman.manage')->with('success', 'Status penjualan tidak valid untuk konfirmasi.');
    }

    public function show($id)
    {
        $pengiriman = Pengiriman::with('penjualan')->findOrFail($id);
        return view('be.pengiriman.show', [
            'title' => 'Detail Pengiriman',
            'pengiriman' => $pengiriman,
        ]);
    }
}