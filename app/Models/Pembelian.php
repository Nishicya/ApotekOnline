<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    protected $fillable = [
        'no_nota',
        'tgl_pembelian',
        'total_bayar',
        'id_distributor',
    ];

    protected $dates = ['tgl_pembelian'];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'id_distributor');
    }

    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class, 'id_pembelian');
    }
}
