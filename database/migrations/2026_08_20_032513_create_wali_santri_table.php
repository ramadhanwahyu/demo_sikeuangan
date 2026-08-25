<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wali_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->string('hubungan', 20)->default('wali'); // ayah, ibu, wali
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi
            $table->unique(['user_id', 'santri_id']);
            
            // Index untuk pencarian
            $table->index('user_id');
            $table->index('santri_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wali_santri');
    }
};