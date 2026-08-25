<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kategori_id')->constrained('kategori_transaksi')->cascadeOnDelete();
            $table->string('jenis', 20); // pemasukan, pengeluaran
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('santri_id')->nullable()->constrained('santri')->nullOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            // Index untuk pencarian dan filter
            $table->index('tanggal');
            $table->index('kategori_id');
            $table->index('jenis');
            $table->index('santri_id');
            $table->index('tingkat_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_kas');
    }
};