<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Obat;
use App\Models\JenisObat;
use App\Models\Keranjang;


class ShopController extends Controller
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

        $obats = Obat::with('jenisObat')
                ->where('stok', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();

        return view('shop.index', [
            'title' => 'Medicine Shop',
            'pelanggan' => $pelanggan,
            'obats' => $obats
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
