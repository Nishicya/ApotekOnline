<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Obat;
use App\Models\JenisObat;
use App\Models\Keranjang;
use App\Models\Pelanggan;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = null;

        if (session('loginId')) {
            $pelanggan = Pelanggan::find(session('loginId'));
        }

        $jenisObats = JenisObat::take(3)->get();
        $obats = Obat::with('jenisObat')
            ->where('stok', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home.index', [
            'title' => 'Home',
            'pelanggan' => $pelanggan,
            'obats' => $obats,
            'jenisObats' => $jenisObats,
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
}
