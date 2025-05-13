<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_metode_bayar');
            $table->date('tgl_penjualan');
            $table->string('url_resep')->nullable();
            $table->double('ongkos_kirim');
            $table->double('biaya_app');
            $table->double('total_bayar');
            $table->enum('status_order', ['Menunggu Konfirmasi', 'Diproses', 'Menunggu Kurir', 'Dibatalkan Pembeli', 'Dibatalkan Penjual', 'Bermasalah', 'Selesai']);
            $table->string('keterangan_status')->nullable();
            $table->unsignedBigInteger('id_jenis_kirim');
            $table->unsignedBigInteger('id_pelanggan');
            $table->timestamps();

            $table->foreign('id_metode_bayar')->references('id')->on('metode_bayars')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_jenis_kirim')->references('id')->on('jenis_pengirimans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
