<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelangganManageController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('be.pelanggan.index', [
            'title' => 'Daftar Pelanggan',
            'pelanggans' => $pelanggans,
        ]);
    }

    public function create()
    {
        return view('be.pelanggan.create', [
            'title' => 'Create Pelanggan',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'email' => 'required|email|unique:pelanggans,email',
            'no_hp' => 'required',
            'password' => 'required|min:6|max:12',
            'alamat1' => 'nullable|string|max:255',
            'kota1' => 'nullable|string|max:255',
            'propinsi1' => 'nullable|string|max:255',
            'kodepos1' => 'nullable|string|max:10',
            'alamat2' => 'nullable|string|max:255',
            'kota2' => 'nullable|string|max:255',
            'propinsi2' => 'nullable|string|max:255',
            'kodepos2' => 'nullable|string|max:10',
            'alamat3' => 'nullable|string|max:255',
            'kota3' => 'nullable|string|max:255',
            'propinsi3' => 'nullable|string|max:255',
            'kodepos3' => 'nullable|string|max:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'url_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = [
            'nama_pelanggan' => $request->nama_pelanggan,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat1' => $request->alamat1 ?? '-',
            'kota1' => $request->kota1 ?? '-',
            'propinsi1' => $request->propinsi1 ?? '-',
            'kodepos1' => $request->kodepos1 ?? '-',
            'alamat2' => $request->alamat2 ?? '-',
            'kota2' => $request->kota2 ?? '-',
            'propinsi2' => $request->propinsi2 ?? '-',
            'kodepos2' => $request->kodepos2 ?? '-',
            'alamat3' => $request->alamat3 ?? '-',
            'kota3' => $request->kota3 ?? '-',
            'propinsi3' => $request->propinsi3 ?? '-',
            'kodepos3' => $request->kodepos3 ?? '-',
        ];

        // Handle file uploads
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pelanggan/fotos', 'public');
            $data['foto'] = $fotoPath;
        } else {
            $data['foto'] = 'default.jpg';
        }

        if ($request->hasFile('url_ktp')) {
            $ktpPath = $request->file('url_ktp')->store('pelanggan/ktp', 'public');
            $data['url_ktp'] = $ktpPath;
        } else {
            $data['url_ktp'] = 'default.jpg';
        }

        Pelanggan::create($data);

        return redirect()->route('pelanggan.manage')->with('success', 'Pelanggan created successfully.');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('be.pelanggan.edit', [
            'title' => 'Edit Pelanggan',
            'pelanggan' => $pelanggan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email,'.$pelanggan->id,
            'no_hp' => 'required|string|max:20|unique:pelanggans,no_hp,'.$pelanggan->id,
            'password' => 'nullable|min:6|max:12',
            'alamat1' => 'required|string|max:255',
            'kota1' => 'required|string|max:255',
            'propinsi1' => 'required|string|max:255',
            'kodepos1' => 'required|string|max:10',
            'alamat2' => 'nullable|string|max:255',
            'kota2' => 'nullable|string|max:255',
            'propinsi2' => 'nullable|string|max:255',
            'kodepos2' => 'nullable|string|max:10',
            'alamat3' => 'nullable|string|max:255',
            'kota3' => 'nullable|string|max:255',
            'propinsi3' => 'nullable|string|max:255',
            'kodepos3' => 'nullable|string|max:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'url_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $updateData = [
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'alamat1' => $validated['alamat1'],
            'kota1' => $validated['kota1'],
            'propinsi1' => $validated['propinsi1'],
            'kodepos1' => $validated['kodepos1'],
            'alamat2' => $validated['alamat2'],
            'kota2' => $validated['kota2'],
            'propinsi2' => $validated['propinsi2'], 
            'kodepos2' => $validated['kodepos2'],
            'alamat3' => $validated['alamat3'],
            'kota3' => $validated['kota3'],
            'propinsi3' => $validated['propinsi3'],
            'kodepos3' => $validated['kodepos3'],
            'foto' => $pelanggan->foto,
            'url_ktp' => $pelanggan->url_ktp,
        ];

        // Handle password update
        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Handle file uploads for update
        if ($request->hasFile('foto')) {
            if ($pelanggan->foto && $pelanggan->foto !== 'default.jpg') {
                Storage::disk('public')->delete($pelanggan->foto);
            }
            $fotoPath = $request->file('foto')->store('pelanggan/fotos', 'public');
            $updateData['foto'] = $fotoPath; // ✅ ganti dari $data ke $updateData
        }
        
        if ($request->hasFile('url_ktp')) {
            if ($pelanggan->url_ktp && $pelanggan->url_ktp !== 'default.jpg') {
                Storage::disk('public')->delete($pelanggan->url_ktp);
            }
            $ktpPath = $request->file('url_ktp')->store('pelanggan/ktp', 'public');
            $updateData['url_ktp'] = $ktpPath; // ✅ ganti dari $data ke $updateData
        }            

        $pelanggan->update($updateData);

        return redirect()->route('pelanggan.manage')->with('success', 'Pelanggan updated successfully.');

    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        // Delete associated files
        if ($pelanggan->foto && $pelanggan->foto !== 'default.jpg') {
            Storage::disk('public')->delete($pelanggan->foto);
        }
        if ($pelanggan->url_ktp && $pelanggan->url_ktp !== 'default.jpg') {
            Storage::disk('public')->delete($pelanggan->url_ktp);
        }
        
        $pelanggan->delete();

        return redirect()->route('pelanggan.manage')->with('success', 'Pelanggan deleted successfully.');
    }
}