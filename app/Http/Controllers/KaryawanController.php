<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('be.karyawan.index', [
            'title' => 'Karyawan'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function daftarKurir()
    {
        $kurirs = User::where('role', 'kurir')->get();
        return view('be.karyawan.daftarkurir.index', [
            'title' => 'Daftar Kurir',
            'kurirs' => $kurirs
        ]);
    }

    public function showKurir($id)
    {
        $kurir = User::where('role', 'kurir')->findOrFail($id);
        return view('be.karyawan.daftarkurir.show', [
            'title' => 'Detail Kurir',
            'kurir' => $kurir
        ]);
    }

    public function destroyKurir($id)
    {
        $kurir = User::where('role', 'kurir')->findOrFail($id);
        $kurir->delete();
        return redirect()->route('karyawan.daftarkurir.index')->with('success', 'Kurir berhasil dihapus.');
    }
}
