<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppRate extends Model
{
    protected $table = 'spp_rates';
    
    protected $fillable = [
        'tahun_ajaran_id',
        'tingkat_id',
        'kelas_id',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel tahun_ajaran
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Relasi ke tabel tingkat
     */
    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class);
    }

    /**
     * Relasi ke tabel kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Scope: Filter berdasarkan tahun ajaran aktif
     */
    public function scopeTahunAjaranAktif($query)
    {
        return $query->whereHas('tahunAjaran', function ($q) {
            $q->where('is_active', true);
        });
    }
}