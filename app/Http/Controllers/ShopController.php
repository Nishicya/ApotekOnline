<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Obat;
use App\Models\JenisObat;
use App\Models\Keranjang;
use Illuminate\Support\Facades\DB;


class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category = null)
    {
        $pelanggan = null;

        if (session('loginId')) {
            $pelanggan = Pelanggan::find(session('loginId'));
        }

        $query = Obat::with('jenisObat')
            ->where('stok', '>', 0);

        if ($category) {
            $query->whereHas('jenisObat', function($q) use ($category) {
                $q->where('jenis', $category);
            });
        }

        $obats = $query->orderBy('created_at', 'desc')
            ->paginate(12);

        $jenisObats = JenisObat::withCount('obats')->get();

        $topSelling = Obat::with('jenisObat')->popular(3)->get();

        $minPrice = Obat::min('harga_jual');
        $maxPrice = Obat::max('harga_jual');

        return view('shop.index', [ 
            'title' => 'Medicine Shop',
            'pelanggan' => $pelanggan,
            'obats' => $obats,
            'jenisObats' => $jenisObats,
            'topSelling' => $topSelling,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'totalProducts' => Obat::count()
        ]);
    }

    public function filter(Request $request)
    {
        $sortBy = $request->input('sort', 'newest');
        $count = $request->input('count', 12);

        $query = Obat::with('jenisObat')
            ->where('stok', '>', 0);

        switch ($sortBy) {
            case 'popular':
                $query->select('obats.*')
                    ->leftJoin('detail_penjualans', 'obats.id', '=', 'detail_penjualans.id_obat')
                    ->selectRaw('obats.*, COALESCE(SUM(detail_penjualans.jumlah_beli), 0) as total_sold')
                    ->groupBy('obats.id')
                    ->orderBy('total_sold', 'desc');
                break;
            case 'price-low':
                $query->orderBy('harga_jual', 'asc');
                break;
            case 'price-high':
                $query->orderBy('harga_jual', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $obats = $query->paginate($count);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.products', ['obats' => $obats])->render(),
                'count' => $obats->count(),
                'total' => $obats->total()
            ]);
        }

        return view('shop.index', [
            'obats' => $obats,
            'title' => 'Medicine Shop',
            'pelanggan' => $request->user(),
            'jenisObats' => JenisObat::withCount('obats')->get(),
            'topSelling' => Obat::with('jenisObat')->popular(3)->get(),
            'minPrice' => Obat::min('harga_jual'),
            'maxPrice' => Obat::max('harga_jual'),
            'totalProducts' => Obat::count()
        ]);
    }

    public function show($id)
    {
        // Debugging
        if (!Obat::where('id', $id)->exists()) {
            abort(404, 'Product not found');
        }

        $pelanggan = null;
        if (session('loginId')) {
            $pelanggan = Pelanggan::find(session('loginId'));
        }

        $obat = Obat::with('jenisObat')->findOrFail($id);

        $relatedProducts = Obat::where('id_jenis', $obat->id_jenis)
            ->where('id', '!=', $id)
            ->where('stok', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('product-detail.index', [
            'title' => $obat->nama_obat . ' - Medicine Detail',
            'pelanggan' => $pelanggan,
            'obat' => $obat,
            'relatedProducts' => $relatedProducts
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
