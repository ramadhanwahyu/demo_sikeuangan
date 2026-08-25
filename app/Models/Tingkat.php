<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tingkat extends Model
{
    protected $table = 'tingkat';
    
    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi ke tabel users (bendahara)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi ke tabel kelas
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    /**
     * Relasi ke tabel santri
     */
    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    /**
     * Relasi ke tabel transaksi_kas
     */
    public function transaksiKas(): HasMany
    {
        return $this->hasMany(TransaksiKas::class);
    }

    /**
     * Relasi ke tabel spp_rates
     */
    public function sppRates(): HasMany
    {
        return $this->hasMany(SppRate::class);
    }
}