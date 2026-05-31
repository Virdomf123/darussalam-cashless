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
        Schema::table('santris', function (Blueprint $table) {
            // Menambahkan kolom tabungan setelah kolom saldo
            // default(0) memastikan santri yang sudah ada punya saldo awal 0
            $table->bigInteger('tabungan')->default(0)->after('saldo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('tabungan');
        });
    }
};