<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modify pengirimans table
        Schema::table('pengirimans', function (Blueprint $table) {
            // Make tgl_kirim nullable
            $table->dateTime('tgl_kirim')->nullable()->change();
            
            // Make telpon_kurir nullable
            $table->string('telpon_kurir')->nullable()->change();
            
            // Update enum status_kirim to include all possible status options
            DB::statement("ALTER TABLE pengirimans MODIFY status_kirim ENUM('Menunggu Pembayaran', 'Menunggu Konfirmasi', 'Diproses', 'Sedang Dikirim', 'Tiba Di Tujuan') DEFAULT 'Menunggu Pembayaran'");
        });
    }

    public function down()
    {
        Schema::table('pengirimans', function (Blueprint $table) {
            $table->dateTime('tgl_kirim')->nullable(false)->change();
            $table->string('telpon_kurir')->nullable(false)->change();
            DB::statement("ALTER TABLE pengirimans MODIFY status_kirim ENUM('Sedang Dikirim', 'Tiba Di Tujuan')");
        });
    }
};
