<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppBill extends Model
{
    protected $table = 'spp_bills';
    
    protected $fillable = [
        'santri_id',
        'tahun_ajaran_id',
        'bulan',
        'nominal',
        'status',
        'tanggal_bayar',
        'transaksi_kas_id',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'nominal' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    /**
     * Relasi ke tabel santri
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relasi ke tabel tahun_ajaran
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Relasi ke tabel transaksi_kas
     */
    public function transaksiKas(): BelongsTo
    {
        return $this->belongsTo(TransaksiKas::class);
    }

    /**
     * Scope: Filter tagihan belum lunas
     */
    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'belum');
    }

    /**
     * Scope: Filter tagihan lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    /**
     * Scope: Filter berdasarkan tingkat (melalui relasi santri)
     */
    public function scopeTingkat($query, $tingkatId)
    {
        return $query->whereHas('santri', function ($q) use ($tingkatId) {
            $q->where('tingkat_id', $tingkatId);
        });
    }
}