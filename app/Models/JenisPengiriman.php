<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPengiriman extends Model
{
    use HasFactory;

    protected $table = 'jenis_pengirimans';

    protected $fillable = [
        'jenis_kirim',
        'nama_ekspedisi',
        'logo_ekspedisi',
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_jenis_kirim');
    }
}
