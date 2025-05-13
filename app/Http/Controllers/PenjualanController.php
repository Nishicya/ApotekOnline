<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\MetodeBayar;
use App\Models\JenisPengiriman;
use App\Models\DetailPenjualan;
use App\Models\User;
use App\Models\Obat;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with(['pelanggan', 'metodeBayar'])->latest()->get();

        return view('be.penjualan.index', [
            'title' => 'Penjualan',
            'penjualan' => $penjualan,
        ]);
    }

    public function show($id)
    {
        $penjualan = Penjualan::with([
            'pelanggan',
            'metodeBayar',
            'jenisPengiriman',
            'detailPenjualans.obat' // pastikan relasi ini ada dan benar
        ])->findOrFail($id);

        return view('be.penjualan.show', [
            'title' => 'Detail Penjualan',
            'penjualan' => $penjualan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('be.penjualan.create', [
            'title' => 'Tambah Data Penjualan',
            'pelanggan' => Pelanggan::orderBy('nama_pelanggan')->get(),
            'metodeBayar' => MetodeBayar::orderBy('metode_pembayaran')->get(),
            'jenisPengiriman' => JenisPengiriman::all(),
        ]);
    }



    public function store(Request $request)
    {
        $detailPenjualans = $request->input('detail_penjualans', []);
        $wajibResep = false;

        // Cek apakah ada obat keras
        foreach ($detailPenjualans as $detail) {
            $obat = Obat::find($detail['id_obat']);
            if ($obat && strtolower($obat->kategori) === 'obat keras') {
                $wajibResep = true;
                break;
            }
        }

        // Validasi umum
        $rules = [
            'id_metode_bayar' => 'required|exists:metode_bayars,id',
            'tgl_penjualan' => 'required|date',
            'ongkos_kirim' => 'required|numeric|min:0',
            'biaya_app' => 'required|numeric|min:0',
            'total_bayar' => 'required|numeric|min:0',
            'status_order' => 'required|string|max:50',
            'keterangan_status' => 'nullable|string|max:255',
            'id_jenis_kirim' => 'required|exists:jenis_pengirimen,id',
            'id_pelanggan' => 'required|exists:pelanggans,id',
        ];

        if ($wajibResep) {
            $rules['url_resep'] = 'required|image|max:2048';
        }

        $validated = $request->validate($rules);

        // Simpan file resep jika ada
        if ($request->hasFile('url_resep')) {
            $file = $request->file('url_resep');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/resep', $filename, 'public');
            $validated['url_resep'] = $path;
        }

        // Simpan penjualan
        $penjualan = Penjualan::create($validated);

        // Simpan detail penjualan
        foreach ($detailPenjualans as $detail) {
            $penjualan->detailPenjualans()->create([
                'id_obat' => $detail['id_obat'],
                'jumlah_beli' => $detail['jumlah_beli'],
                'harga_beli' => $detail['harga_beli'],
                'subtotal' => $detail['subtotal'],
            ]);
        }

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil disimpan.');
    }

    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        return view('be.penjualan.edit', [
            'title' => 'Edit Data Penjualan',
            'penjualan' => $penjualan,
            'pelanggan' => Pelanggan::all(),
            'metodeBayar' => MetodeBayar::all(),
            'jenisKirim' => JenisPengiriman::all(),
        ]);
    }


    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $detailPenjualans = $request->input('detail_penjualans', []);
        $wajibResep = false;

        // Cek apakah ada obat keras
        foreach ($detailPenjualans as $detail) {
            $obat = Obat::find($detail['id_obat']);
            if ($obat && strtolower($obat->kategori) === 'obat keras') {
                $wajibResep = true;
                break;
            }
        }

        // Validasi
        $rules = [
            'id_metode_bayar' => 'required|exists:metode_bayars,id',
            'tgl_penjualan' => 'required|date',
            'ongkos_kirim' => 'required|numeric|min:0',
            'biaya_app' => 'required|numeric|min:0',
            'total_bayar' => 'required|numeric|min:0',
            'status_order' => 'required|string|max:50',
            'keterangan_status' => 'nullable|string|max:255',
            'id_jenis_kirim' => 'required|exists:jenis_pengirimen,id',
            'id_pelanggan' => 'required|exists:pelanggans,id',
        ];

        if ($wajibResep) {
            $rules['url_resep'] = 'nullable|image|max:2048';
        }

        $validated = $request->validate($rules);

        // Handle file upload
        if ($wajibResep && $request->hasFile('url_resep')) {
            // Hapus file lama jika ada
            if ($penjualan->url_resep && Storage::disk('public')->exists($penjualan->url_resep)) {
                Storage::disk('public')->delete($penjualan->url_resep);
            }

            $file = $request->file('url_resep');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/resep', $filename, 'public');
            $validated['url_resep'] = $path;
        }

        $penjualan->update($validated);

        // Hapus dan buat ulang detail penjualan
        $penjualan->detailPenjualans()->delete();

        foreach ($detailPenjualans as $detail) {
            $penjualan->detailPenjualans()->create([
                'id_obat' => $detail['id_obat'],
                'jumlah_beli' => $detail['jumlah_beli'],
                'harga_beli' => $detail['harga_beli'],
                'subtotal' => $detail['subtotal'],
            ]);
        }

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penjualan = penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
    }
}

