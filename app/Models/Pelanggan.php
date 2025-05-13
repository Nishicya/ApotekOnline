<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Authenticatable
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama_pelanggan',
        'email',
        'password',
        'no_hp',
        'alamat1',
        'kota1',
        'propinsi1',
        'kodepos1',
        'alamat2',
        'kota2',
        'propinsi2',
        'kodepos2',
        'alamat3',
        'kota3',
        'propinsi3',
        'kodepos3',
        'foto',
        'url_ktp',
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_pelanggan');
    }

    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'id_pelanggan');
    }
}
