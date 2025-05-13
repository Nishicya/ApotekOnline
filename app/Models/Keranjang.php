<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjangs';

    protected $fillable = [
        'id_pelanggan',
        'id_obat',
        'jumlah_order',
        'harga',
        'subtotal',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}