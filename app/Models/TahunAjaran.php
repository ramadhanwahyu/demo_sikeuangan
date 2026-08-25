<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';
    
    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke tabel spp_rates
     */
    public function sppRates(): HasMany
    {
        return $this->hasMany(SppRate::class);
    }

    /**
     * Relasi ke tabel spp_bills
     */
    public function sppBills(): HasMany
    {
        return $this->hasMany(SppBill::class);
    }

    /**
     * Scope: Filter tahun ajaran aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}