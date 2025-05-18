<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats';

    protected $fillable = [
        'nama_obat',
        'id_jenis',
        'harga_jual',
        'deskripsi_obat',
        'foto1',
        'foto2',
        'foto3',
        'stok',
    ];

    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'id_jenis');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_obat');
    }

    public function getTotalSoldAttribute()
    {
        return $this->detailPenjualans()->sum('jumlah_beli');
    }

    public function scopePopular($query, $limit = 3)
    {
        return $query->withCount(['detailPenjualans as total_sold' => function($query) {
                $query->select(DB::raw('COALESCE(SUM(jumlah_beli), 0)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->take($limit);
    }
}