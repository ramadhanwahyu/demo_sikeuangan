<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 30)->unique();
            $table->string('nama_lengkap', 150);
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->year('tahun_masuk');
            $table->string('status', 20)->default('aktif'); // aktif, lulus, keluar
            $table->decimal('saldo_uang_jajan', 15, 2)->default(0);
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('tingkat_id');
            $table->index('kelas_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};