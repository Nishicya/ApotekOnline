<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeBayar extends Model
{
    use HasFactory;

    protected $table = 'metode_bayars';

    protected $fillable = [
        'metode_pembayaran',
        'tempat_bayar',
        'no_rekening',
        'url_logo',
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_metode_bayar');
    }
}
