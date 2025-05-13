<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Distributor;
use App\Models\Obat;
use App\Models\DetailPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with(['distributor', 'detailPembelians.obat'])
            ->latest()
            ->get();
        
        return view('be.pembelian.index', [
            'title' => 'Data Pembelian',
            'pembelians' => $pembelians
        ]);
    }

    public function create()
    {
        return view('be.pembelian.create', [
            'title' => 'Tambah Pembelian',
            'distributors' => Distributor::orderBy('nama_distributor')->get(),
            'obats' => Obat::orderBy('nama_obat')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pembelian' => 'required|date',
            'id_distributor' => 'required|exists:distributors,id',
            'obat_id' => 'required|array|min:1',
            'obat_id.*' => 'exists:obats,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'integer|min:1',
            'harga_beli' => 'required|array|min:1',
            'harga_beli.*' => 'numeric|min:0',
            'total_bayar' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Generate nota
            $nota = 'PB-' . date('Ymd') . '-' . Str::upper(Str::random(4));
            
            // Create pembelian
            $pembelian = Pembelian::create([
                'no_nota' => $nota,
                'tgl_pembelian' => $validated['tgl_pembelian'],
                'total_bayar' => $validated['total_bayar'],
                'id_distributor' => $validated['id_distributor'],
                'id_user' => auth()->id() // Tambahkan user yang melakukan pembelian
            ]);

            // Create detail pembelian dan update stok
            foreach ($validated['obat_id'] as $index => $obatId) {
                $jumlah = $validated['jumlah'][$index];
                $hargaBeli = $validated['harga_beli'][$index];
                
                // Cek stok cukup (jika ada validasi maksimal)
                $obat = Obat::findOrFail($obatId);
                
                $pembelian->detailPembelians()->create([
                    'id_obat' => $obatId,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $jumlah * $hargaBeli
                ]);

                // Update stok obat
                $obat->increment('stok', $jumlah);
            }

            DB::commit();
            
            return redirect()
                ->route('pembelian.index')
                ->with('success', 'Pembelian berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
    }
    
    public function show($id)
    {
        $pembelian = Pembelian::with(['distributor', 'detailPembelians.obat', 'user'])
            ->findOrFail($id);
        
        return view('be.pembelian.show', [
            'title' => 'Detail Pembelian',
            'pembelian' => $pembelian
        ]);
    }

    public function edit($id)
    {
        $pembelian = Pembelian::with(['detailPembelians', 'distributor'])
            ->findOrFail($id);
        
        return view('be.pembelian.edit', [
            'title' => 'Edit Pembelian',
            'pembelian' => $pembelian,
            'distributors' => Distributor::orderBy('nama_distributor')->get(),
            'obats' => Obat::orderBy('nama_obat')->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tgl_pembelian' => 'required|date',
            'id_distributor' => 'required|exists:distributors,id',
            'obat_id' => 'required|array|min:1',
            'obat_id.*' => 'exists:obats,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'integer|min:1',
            'harga_beli' => 'required|array|min:1',
            'harga_beli.*' => 'numeric|min:0',
            'total_bayar' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $pembelian = Pembelian::findOrFail($id);
            
            // Revert stock changes from old details
            foreach ($pembelian->detailPembelians as $detail) {
                Obat::find($detail->id_obat)
                    ->decrement('stok', $detail->jumlah);
            }
            
            // Delete old details
            $pembelian->detailPembelians()->delete();
            
            // Update pembelian
            $pembelian->update([
                'tgl_pembelian' => $validated['tgl_pembelian'],
                'total_bayar' => $validated['total_bayar'],
                'id_distributor' => $validated['id_distributor']
            ]);
            
            // Create new details and update stock
            foreach ($validated['obat_id'] as $index => $obatId) {
                $jumlah = $validated['jumlah'][$index];
                $hargaBeli = $validated['harga_beli'][$index];
                
                $pembelian->detailPembelians()->create([
                    'id_obat' => $obatId,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $jumlah * $hargaBeli
                ]);

                Obat::find($obatId)
                    ->increment('stok', $jumlah);
            }

            DB::commit();
            return redirect()
                ->route('pembelian.index')
                ->with('success', 'Pembelian berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pembelian: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pembelian = Pembelian::findOrFail($id);
            
            // Revert stock changes
            foreach ($pembelian->detailPembelians as $detail) {
                Obat::find($detail->id_obat)
                    ->decrement('stok', $detail->jumlah);
            }
            
            // Delete details first
            $pembelian->detailPembelians()->delete();
            $pembelian->delete();
            
            DB::commit();
            return redirect()
                ->route('pembelian.index')
                ->with('success', 'Pembelian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Gagal menghapus pembelian: ' . $e->getMessage());
        }
    }

    public function printNota($id)
    {
        $pembelian = Pembelian::with(['distributor', 'detailPembelians.obat', 'user'])
            ->findOrFail($id);
            
        return view('be.pembelian.nota', [
            'title' => 'Nota Pembelian',
            'pembelian' => $pembelian
        ]);
    }
}