<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';
    
    protected $fillable = [
        'nama',
        'tingkat_id',
        'urutan',
    ];

    /**
     * Relasi ke tabel tingkat
     */
    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class);
    }

    /**
     * Relasi ke tabel santri
     */
    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    /**
     * Relasi ke tabel spp_rates
     */
    public function sppRates(): HasMany
    {
        return $this->hasMany(SppRate::class);
    }
}