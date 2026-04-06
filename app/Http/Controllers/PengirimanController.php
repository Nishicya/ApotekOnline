<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PengirimanController extends Controller
{ 
    public function index()
    {
        $pengiriman = Pengiriman::with(['penjualan.jenisPengiriman', 'kurir'])->latest()->get();

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

    /**
     * Confirm dan assign kurir ke pengiriman
     */
    public function confirm(Request $request, $id)
    {
        try {
            Log::info('Confirm pengiriman attempt', ['id' => $id, 'kurir_id' => $request->id_kurir]);
            
            $pengiriman = Pengiriman::findOrFail($id);

            $validated = $request->validate([
                'id_kurir' => 'required|exists:users,id',
            ]);

            Log::info('Validation passed for confirm', ['validated' => $validated]);

            $pengiriman->update([
                'id_kurir' => $request->id_kurir,
                'status_kirim' => 'Sedang Dikirim',
                'tgl_konfirmasi' => now(),
            ]);

            Log::info('Pengiriman updated successfully', ['pengiriman' => $pengiriman->toArray()]);

            // Update penjualan status
            if ($pengiriman->penjualan) {
                $pengiriman->penjualan->update([
                    'status_order' => 'Diproses',
                    'keterangan_status' => 'Sedang dikirim'
                ]);
                Log::info('Penjualan updated', ['penjualan' => $pengiriman->penjualan->toArray()]);
            }

            return redirect()->route('daftarpengiriman.index')->with('success', 'Pengiriman dikonfirmasi dan kurir ditugaskan.');
        } catch (\Exception $e) {
            Log::error('Error confirming pengiriman: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('daftarpengiriman.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cancel pengiriman
     */
    public function cancel(Request $request, $id)
    {
        try {
            Log::info('Cancel pengiriman attempt', ['id' => $id, 'alasan' => $request->alasan]);
            
            $pengiriman = Pengiriman::findOrFail($id);

            $validated = $request->validate([
                'alasan' => 'required|string|max:255',
            ]);

            Log::info('Validation passed for cancel', ['validated' => $validated]);

            $pengiriman->update([
                'status_kirim' => 'Dibatalkan',
                'keterangan' => $request->alasan,
            ]);

            Log::info('Pengiriman cancelled successfully', ['pengiriman' => $pengiriman->toArray()]);

            // Update penjualan status
            if ($pengiriman->penjualan) {
                $pengiriman->penjualan->update([
                    'status_order' => 'Dibatalkan',
                    'keterangan_status' => 'Pengiriman dibatalkan: ' . $request->alasan
                ]);
                Log::info('Penjualan cancelled', ['penjualan' => $pengiriman->penjualan->toArray()]);
            }

            return redirect()->route('daftarpengiriman.index')->with('success', 'Pengiriman dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Error cancelling pengiriman: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('daftarpengiriman.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
