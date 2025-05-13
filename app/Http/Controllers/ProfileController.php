<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;

class ProfileController extends Controller
{
    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        return view('fe.profile', [
            'title' => 'Profile Pelanggan',
            'pelanggan' => $pelanggan,
        ]);
    }

    public function update(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

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

        try {
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
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
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

            return redirect()->route('profile')->with([
                'swal' => [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => 'profile updated!',
                    'timer' => 1500
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with([
                'swal' => [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Fail to update profile: ' . $e->getMessage(),
                    'timer' => 3000
                ]
            ])->withInput();
        }
    }
}