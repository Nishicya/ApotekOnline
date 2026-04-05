<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengirimans';

    protected $fillable = [
        'id_penjualan',
        'id_kurir',
        'no_invoice',
        'tgl_kirim',
        'tgl_konfirmasi',
        'tgl_tiba', 
        'status_kirim',
        'nama_kurir',
        'telpon_kurir',
        'bukti_foto',
        'keterangan',
    ];

    /**
     * Get the sale that owns the delivery.
     */
    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    /**
     * Get the jenis pengiriman that owns the delivery.
     */
    public function jenisPengiriman(): BelongsTo
    {
        return $this->belongsTo(JenisPengiriman::class, 'id_jenis_kirim');
    }

    /**
     * Get the kurir (User dengan role kurir)
     */
    public function kurir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_kurir');
    }
}