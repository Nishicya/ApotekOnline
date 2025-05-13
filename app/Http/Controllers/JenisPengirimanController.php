<?php

namespace App\Http\Controllers;

use App\Models\JenisPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JenisPengirimanController extends Controller
{
    public function index()
    {
        $jenisPengiriman = JenisPengiriman::all();
        
        return view('be.jenis-pengiriman.index', [
            'title' => 'Manajemen Jenis Pengiriman',
            'jenisPengiriman' => $jenisPengiriman
        ]);
    }

    public function create()
    {
        $jenisKirimOptions = [
            'ekonomi' => 'Ekonomi',
            'kargo' => 'Kargo',
            'regular' => 'Regular', 
            'same day' => 'Same Day',
            'standar' => 'Standar'
        ];
        
        return view('be.jenis-pengiriman.create', [
            'title' => 'Tambah Jenis Pengiriman',
            'jenisKirimOptions' => $jenisKirimOptions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kirim' => 'required|in:ekonomi,kargo,regular,same day,standar',
            'nama_ekspedisi' => 'required|string|max:100',
            'logo_ekspedisi' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($request->hasFile('logo_ekspedisi')) {
            $validated['logo_ekspedisi'] = $request->file('logo_ekspedisi')->store('ekspedisi-logos', 'public');
        }

        JenisPengiriman::create($validated);

        return redirect()->route('jenis-pengiriman.manage')->with('success', 'Jenis pengiriman berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jenisPengiriman = JenisPengiriman::findOrFail($id);
        $jenisKirimOptions = [
            'ekonomi' => 'Ekonomi',
            'kargo' => 'Kargo',
            'regular' => 'Regular',
            'same day' => 'Same Day',
            'standar' => 'Standar'
        ];
        
        return view('be.jenis-pengiriman.edit', [
            'title' => 'Edit Jenis Pengiriman',
            'jenisPengiriman' => $jenisPengiriman,
            'jenisKirimOptions' => $jenisKirimOptions
        ]);
    }

    public function show($id)
    {
        $jenisPengiriman = JenisPengiriman::findOrFail($id);
        $jenisLabels = [
            'ekonomi' => 'Ekonomi',
            'kargo' => 'Kargo',
            'regular' => 'Regular',
            'same day' => 'Same Day',
            'standar' => 'Standar'
        ];

        return view('be.jenis-pengiriman.show', [
            'title' => 'Detail Jenis Pengiriman',
            'jenisPengiriman' => $jenisPengiriman,
            'jenisLabels' => $jenisLabels
        ]);
    }

    public function update(Request $request, $id)
    {
        $jenisPengiriman = JenisPengiriman::findOrFail($id);

        $validated = $request->validate([
            'jenis_kirim' => 'required|in:ekonomi,kargo,regular,same day,standar',
            'nama_ekspedisi' => 'required|string|max:100',
            'logo_ekspedisi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_logo' => 'nullable|boolean'
        ]);

        // Handle logo deletion
        if ($request->has('delete_logo') && $request->input('delete_logo')) {
            if ($jenisPengiriman->logo_ekspedisi) {
                Storage::disk('public')->delete($jenisPengiriman->logo_ekspedisi);
                $validated['logo_ekspedisi'] = null;
            }
        }
        // Handle logo upload
        elseif ($request->hasFile('logo_ekspedisi')) {
            if ($jenisPengiriman->logo_ekspedisi) {
                Storage::disk('public')->delete($jenisPengiriman->logo_ekspedisi);
            }
            $validated['logo_ekspedisi'] = $request->file('logo_ekspedisi')->store('ekspedisi-logos', 'public');
        } else {
            $validated['logo_ekspedisi'] = $jenisPengiriman->logo_ekspedisi;
        }

        $jenisPengiriman->update($validated);

        return redirect()->route('jenis-pengiriman.manage')->with('success', 'Jenis pengiriman berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jenisPengiriman = JenisPengiriman::findOrFail($id);

        if ($jenisPengiriman->logo_ekspedisi) {
            Storage::disk('public')->delete($jenisPengiriman->logo_ekspedisi);
        }

        $jenisPengiriman->delete();

        return redirect()->route('jenis-pengiriman.manage')->with('success', 'Jenis pengiriman berhasil dihapus');
    }
}