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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nim'); // Menghubungkan transaksi ke NIM santri tertentu
            $table->string('produk'); // Nama barang yang dibeli (Bakso, Buku, dll)
            $table->integer('harga'); // Harga barang
            $table->string('kategori')->nullable(); // Kategori seperti Makanan, Alat Tulis, dll
            $table->timestamps(); // Mencatat created_at (waktu transaksi) secara otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};