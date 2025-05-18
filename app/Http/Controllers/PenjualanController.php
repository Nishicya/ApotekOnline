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
            'title' => 'Daftar Penjualan',
            'penjualan' => $penjualan,
        ]);
    }

    public function show($id)
    {
        $penjualan = Penjualan::with([
            'pelanggan',
            'metodeBayar',
            'jenisPengiriman',
            'detailPenjualans.obat'
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
            'obats' => Obat::all() // Add this line
        ]);
    }



    public function store(Request $request)
    {
        $detailPenjualans = $request->input('detail_penjualans', []);
        $wajibResep = false;

        foreach ($detailPenjualans as $detail) {
            $obat = Obat::find($detail['id_obat']);
            if ($obat && strtolower($obat->kategori) === 'obat keras') {
                $wajibResep = true;
                break;
            }
        }

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

        if ($request->hasFile('url_resep')) {
            $file = $request->file('url_resep');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/resep', $filename, 'public');
            $validated['url_resep'] = $path;
        }

        $penjualan = Penjualan::create($validated);

        foreach ($detailPenjualans as $detail) {
            $penjualan->detailPenjualans()->create([
                'id_obat' => $detail['id_obat'],
                'jumlah_beli' => $detail['jumlah_beli'],
                'harga_beli' => $detail['harga_beli'],
                'subtotal' => $detail['subtotal'],
            ]);
        }

        return redirect()->route('penjualan.manage')->with('success', 'Data penjualan berhasil disimpan.');
    }

    public function edit($id)
    {
        $penjualan = Penjualan::with(['detailPenjualans.obat'])->findOrFail($id);

        return view('be.penjualan.edit', [
            'title' => 'Edit Data Penjualan',
            'penjualan' => $penjualan,
            'pelanggan' => Pelanggan::orderBy('nama_pelanggan')->get(),
            'metodeBayar' => MetodeBayar::orderBy('metode_pembayaran')->get(),
            'jenisPengiriman' => JenisPengiriman::all(),
            'obats' => Obat::all(),
            'statusOptions' => Penjualan::getStatusOrderOptions()
        ]);
    }

    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::with(['detailPenjualans'])->findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'tgl_penjualan' => 'required|date',
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'id_metode_bayar' => 'required|exists:metode_bayars,id',
            'id_jenis_kirim' => 'required|exists:jenis_pengirimen,id',
            'ongkos_kirim' => 'required|numeric|min:0',
            'biaya_app' => 'required|numeric|min:0',
            'status_order' => 'required|string|max:50',
            'keterangan_status' => 'nullable|string|max:255',
            'id_obat' => 'required|array',
            'id_obat.*' => 'exists:obats,id',
            'jumlah_beli' => 'required|array',
            'jumlah_beli.*' => 'numeric|min:1',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'numeric|min:0',
        ]);

        // Handle file upload
        if ($request->hasFile('url_resep')) {
            // Delete old file if exists
            if ($penjualan->url_resep && Storage::disk('public')->exists($penjualan->url_resep)) {
                Storage::disk('public')->delete($penjualan->url_resep);
            }

            $file = $request->file('url_resep');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/resep', $filename, 'public');
            $validated['url_resep'] = $path;
        }

        // Calculate total
        $total = 0;
        foreach ($request->id_obat as $key => $obatId) {
            $subtotal = $request->jumlah_beli[$key] * $request->harga_beli[$key];
            $total += $subtotal;
        }
        $validated['total_bayar'] = $total + $request->ongkos_kirim + $request->biaya_app;

        // Update penjualan
        $penjualan->update($validated);

        // Delete old details
        $penjualan->detailPenjualans()->delete();

        // Create new details
        foreach ($request->id_obat as $key => $obatId) {
            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id,
                'id_obat' => $obatId,
                'jumlah_beli' => $request->jumlah_beli[$key],
                'harga_beli' => $request->harga_beli[$key],
                'subtotal' => $request->jumlah_beli[$key] * $request->harga_beli[$key]
            ]);
        }

        return redirect()->route('penjualan.show', $penjualan->id)
            ->with('success', 'Data penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penjualan = penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('penjualan.manage')->with('success', 'Data penjualan berhasil dihapus.');
    }
}

