<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    protected $table = 'santri';
    
    protected $fillable = [
        'nis',
        'nama_lengkap',
        'tanggal_lahir',
        'alamat',
        'tingkat_id',
        'kelas_id',
        'tahun_masuk',
        'status',
        'saldo_uang_jajan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_masuk' => 'integer',
        'saldo_uang_jajan' => 'decimal:2',
    ];

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
     * Relasi ke tabel wali_santri
     */
    public function waliSantri(): HasMany
    {
        return $this->hasMany(WaliSantri::class);
    }

    /**
     * Relasi ke tabel transaksi_kas
     */
    public function transaksiKas(): HasMany
    {
        return $this->hasMany(TransaksiKas::class);
    }

    /**
     * Relasi ke tabel spp_bills
     */
    public function sppBills(): HasMany
    {
        return $this->hasMany(SppBill::class);
    }

    /**
     * Scope: Filter santri aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope: Filter berdasarkan tingkat
     */
    public function scopeTingkat($query, $tingkatId)
    {
        return $query->where('tingkat_id', $tingkatId);
    }
}