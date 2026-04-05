<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update enum status_kirim dengan semua status yang mungkin
        DB::statement("ALTER TABLE pengirimans MODIFY status_kirim ENUM('Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Diproses', 'Sedang Dikirim', 'Tiba Di Tujuan') DEFAULT 'Menunggu Pembayaran'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE pengirimans MODIFY status_kirim ENUM('Sedang Dikirim', 'Tiba Di Tujuan')");
    }
};
