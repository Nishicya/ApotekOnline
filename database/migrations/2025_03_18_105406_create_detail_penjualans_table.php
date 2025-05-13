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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penjualan');
            $table->unsignedBigInteger('id_obat');
            $table->integer('jumlah_beli');
            $table->double('harga_beli');
            $table->double('subtotal');
            $table->timestamps();

            $table->foreign('id_penjualan')->references('id')->on('penjualans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_obat')->references('id')->on('obats')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
