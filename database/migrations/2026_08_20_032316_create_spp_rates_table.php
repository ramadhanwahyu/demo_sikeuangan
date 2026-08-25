<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
            
            // Unique constraint untuk kombinasi
            $table->unique(['tahun_ajaran_id', 'tingkat_id', 'kelas_id']);
            
            // Index untuk pencarian
            $table->index('tahun_ajaran_id');
            $table->index('tingkat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_rates');
    }
};