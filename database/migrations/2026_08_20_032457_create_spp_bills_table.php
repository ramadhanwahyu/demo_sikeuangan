<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->integer('bulan'); // 1-12
            $table->decimal('nominal', 15, 2);
            $table->string('status', 20)->default('belum'); // belum, lunas
            $table->date('tanggal_bayar')->nullable();
            $table->foreignId('transaksi_kas_id')->nullable()->constrained('transaksi_kas')->nullOnDelete();
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplicate tagihan
            $table->unique(['santri_id', 'tahun_ajaran_id', 'bulan']);
            
            // Index untuk pencarian
            $table->index('santri_id');
            $table->index('tahun_ajaran_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_bills');
    }
};