<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('jenis', 20); // pemasukan, pengeluaran
            $table->boolean('is_uang_jajan')->default(false);
            $table->boolean('is_sistem')->default(false);
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('jenis');
            $table->unique(['nama', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_transaksi');
    }
};