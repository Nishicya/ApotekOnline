<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\JenisObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::with('jenisObat')->latest()->get();
        $jenisObats = JenisObat::all();

        return view('be.obat.index', [
            'title' => 'Obat Management',
            'obats' => $obats,
            'jenisObats' => $jenisObats,
        ]);
    }

    public function create()
    {
        $jenisObats = JenisObat::all();

        return view('be.obat.create', [
            'title' => 'Add New Medicine',
            'jenisObats' => $jenisObats,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'id_jenis' => 'required|exists:jenis_obats,id',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi_obat' => 'required|string',
            'stok' => 'required|integer|min:0',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            $imageFields = ['foto1', 'foto2', 'foto3'];
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    $validated[$field] = $request->file($field)->store('obat-images', 'public');
                }
            }

            Obat::create($validated);

            return redirect()->route('obat.manage')->with('success', 'Medicine added successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to add medicine. Error: '.$e->getMessage());
        }
    }

    public function show(Obat $obat)
    {
        $obat->load('jenisObat');
        
        return view('be.obat.show', [
            'title' => 'Medicine Detail',
            'obat' => $obat,
        ]);
    }

    public function edit(Obat $obat)
    {
        $jenisObats = JenisObat::all();

        return view('be.obat.edit', [
            'title' => 'Edit Medicine',
            'obat' => $obat,
            'jenisObats' => $jenisObats,
        ]);
    }

    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'id_jenis' => 'required|exists:jenis_obats,id',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi_obat' => 'required|string',
            'stok' => 'required|integer|min:0',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_foto1' => 'nullable|boolean',
            'delete_foto2' => 'nullable|boolean',
            'delete_foto3' => 'nullable|boolean',
        ]);

        try {
            $imageFields = ['foto1', 'foto2', 'foto3'];
            
            foreach ($imageFields as $field) {
                // Handle file deletion
                if ($request->has('delete_'.$field) && $request->input('delete_'.$field)) {
                    if ($obat->$field) {
                        Storage::disk('public')->delete($obat->$field);
                        $validated[$field] = null;
                    }
                }
                // Handle file upload
                elseif ($request->hasFile($field)) {
                    if ($obat->$field) {
                        Storage::disk('public')->delete($obat->$field);
                    }
                    $validated[$field] = $request->file($field)->store('obat-images', 'public');
                } else {
                    $validated[$field] = $obat->$field;
                }
            }

            $obat->update($validated);

            return redirect()->route('obat.manage')->with('success', 'Medicine updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update medicine. Error: '.$e->getMessage());
        }
    }

    public function destroy(Obat $obat)
    {
        try {
            $imageFields = ['foto1', 'foto2', 'foto3'];
            foreach ($imageFields as $field) {
                if ($obat->$field) {
                    Storage::disk('public')->delete($obat->$field);
                }
            }

            $obat->delete();

            return redirect()->route('obat.manage')->with('success', 'Medicine deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete medicine. Error: '.$e->getMessage());
        }
    }
}