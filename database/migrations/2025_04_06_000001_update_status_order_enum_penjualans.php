<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Alter enum to include 'Menunggu Pembayaran'
        // Since MySQL 5.7.6+, we can modify enum directly
        DB::statement("ALTER TABLE penjualans MODIFY status_order ENUM('Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Diproses', 'Menunggu Kurir', 'Dikirim', 'Selesai', 'Dibatalkan') DEFAULT 'Menunggu Pembayaran'");
    }

    public function down()
    {
        // Revert to original enum
        DB::statement("ALTER TABLE penjualans MODIFY status_order ENUM('Menunggu Konfirmasi', 'Diproses', 'Menunggu Kurir', 'Dibatalkan Pembeli', 'Dibatalkan Penjual', 'Bermasalah', 'Selesai')");
    }
};
