<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::latest()->get();
        return view('be.distributor.index', [
            'title' => 'Data Distributor',
            'distributors' => $distributors
        ]);
    }

    public function create()
    {
        return view('be.distributor.create', [
            'title' => 'Tambah Distributor'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_distributor' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string'
        ]);

        Distributor::create($validated);

        return redirect()->route('distributor.index')
            ->with('success', 'Data distributor berhasil ditambahkan');
    }

   public function show($id)
    {
        $distributor = Distributor::findOrFail($id);
        
        if(request()->ajax()) {
            return response()->json([
                'nama_distributor' => $distributor->nama_distributor,
                'telepon' => $distributor->telepon,
                'alamat' => $distributor->alamat
            ]);
        }
        
        return view('be.distributor.show', [
            'title' => 'Detail Distributor',
            'distributor' => $distributor
        ]);
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        return view('be.distributor.edit', [
            'title' => 'Edit Distributor',
            'distributor' => $distributor
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_distributor' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string'
        ]);

        $distributor = Distributor::findOrFail($id);
        $distributor->update($validated);

        return redirect()->route('distributor.index')
            ->with('success', 'Data distributor berhasil diperbarui');
    }

    public function destroy($id)
    {
        $distributor = Distributor::findOrFail($id);
        $distributor->delete();

        return redirect()->route('distributor.index')
            ->with('success', 'Data distributor berhasil dihapus');
    }
}