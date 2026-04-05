<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->string('alamat_pengiriman')->nullable()->after('id_pelanggan');
            $table->text('catatan')->nullable()->after('alamat_pengiriman');
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn('alamat_pengiriman');
            $table->dropColumn('catatan');
        });
    }
};
