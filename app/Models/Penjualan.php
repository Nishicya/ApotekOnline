<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penjualan extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan database
    protected $table = 'penjualans';

    protected $fillable = [
        'id_metode_bayar',
        'tgl_penjualan',
        'url_resep',
        'ongkos_kirim',
        'biaya_app',
        'total_bayar',
        'status_order',
        'keterangan_status',
        'id_jenis_kirim',
        'id_pelanggan',
    ];

    // === RELASI ===

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function metodeBayar()
    {
        return $this->belongsTo(MetodeBayar::class, 'id_metode_bayar');
    }

    public function jenisPengiriman()
    {
        return $this->belongsTo(JenisPengiriman::class, 'id_jenis_kirim');
    }

    public function pengiriman(): HasOne
    {
        return $this->hasOne(Pengiriman::class, 'id_penjualan');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }

    // === ENUM HELPER OPTIONAL ===
    public static function getStatusOrderOptions()
    {
        return [
            'Menunggu Konfirmasi',
            'Diproses',
            'Menunggu Kurir',
            'Dikirim',
            'Selesai',
            'Dibatalkan'
        ];
    }

    public function getStatusColorAttribute()
    {
        switch($this->status_order) {
            case 'Menunggu Konfirmasi': return 'warning';
            case 'Diproses': return 'info';
            case 'Menunggu Kurir': return 'primary';
            case 'Dikirim': return 'success';
            case 'Selesai': return 'dark';
            case 'Dibatalkan': return 'danger';
            default: return 'secondary';
        }
    }
}
