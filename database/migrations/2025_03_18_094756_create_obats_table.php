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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat', 100);
            $table->unsignedBigInteger('id_jenis');
            $table->integer('harga_jual');
            $table->text('deskripsi_obat');
            $table->string('foto1')->nullable();
            $table->string('foto2')->nullable();
            $table->string('foto3')->nullable();
            $table->integer('stok')->default(0);
            $table->timestamps();

            // Menambahkan foreign key dengan cara manual
            $table->foreign('id_jenis')->references('id')->on('jenis_obats')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
