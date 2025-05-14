<?php

namespace App\Http\Controllers;

use App\Models\JenisObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JenisObatController extends Controller
{
    public function index()
    {
        $jenisObats = JenisObat::all();
        return view('be.jenis_obat.index', [
            'title' => 'Kategori Obat Management',
            'jenisObats' => $jenisObats,
        ]);
    }

    public function create()
    {
        return view('be.jenis_obat.create', [
            'title' => 'Add New Medicine Type',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|string|max:255|unique:jenis_obats',
            'deskripsi_jenis' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('jenis-obat-images', 'public');
        }

        JenisObat::create($validated);
        return redirect()->route('jenis-obat.manage')->with('success', 'Medicine type added successfully');
    }

    public function edit($id)
    {
        $jenisObat = JenisObat::findOrFail($id);
        return view('be.jenis_obat.edit', [
            'title' => 'Edit Medicine Type',
            'jenisObat' => $jenisObat,
        ]);
    }

    public function update(Request $request, $id)
    {
        $jenisObat = JenisObat::findOrFail($id);

        $validated = $request->validate([
            'jenis' => 'required|string|max:255|unique:jenis_obats,jenis,'.$jenisObat->id,
            'deskripsi_jenis' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            // Delete old image if exists
            if ($jenisObat->image_url) {
                Storage::disk('public')->delete($jenisObat->image_url);
            }
            $validated['image_url'] = $request->file('image_url')->store('jenis-obat-images', 'public');
        }

        $jenisObat->update($validated);
        return redirect()->route('jenis-obat.manage')->with('success', 'Medicine type updated successfully');
    }

    public function destroy($id)
    {
        $jenisObat = JenisObat::findOrFail($id);
        
        if ($jenisObat->obats()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete this type because it is being used by some medicines');
        }

        // Delete image if exists
        if ($jenisObat->image_url) {
            Storage::disk('public')->delete($jenisObat->image_url);
        }

        $jenisObat->delete();
        return redirect()->route('jenis-obat.manage')->with('success', 'Medicine type deleted successfully');
    }
}