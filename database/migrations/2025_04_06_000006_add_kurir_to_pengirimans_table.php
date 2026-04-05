<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengirimans', function (Blueprint $table) {
            // Tambah field untuk relasi ke kurir (User dengan role kurir)
            $table->unsignedBigInteger('id_kurir')->nullable()->after('nama_kurir');
            
            // Tambah field untuk tracking konfirmasi
            $table->timestamp('tgl_konfirmasi')->nullable()->after('tgl_kirim');
            
            // Add foreign key ke users (kurir)
            $table->foreign('id_kurir')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('pengirimans', function (Blueprint $table) {
            $table->dropForeign(['id_kurir']);
            $table->dropColumn(['id_kurir', 'tgl_konfirmasi']);
        });
    }
};
