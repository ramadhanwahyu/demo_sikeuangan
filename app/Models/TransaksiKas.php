<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiKas extends Model
{
    protected $table = 'transaksi_kas';
    
    protected $fillable = [
        'tanggal',
        'kategori_id',
        'jenis',
        'jumlah',
        'keterangan',
        'santri_id',
        'tingkat_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel kategori_transaksi
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriTransaksi::class, 'kategori_id');
    }

    /**
     * Relasi ke tabel santri
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relasi ke tabel tingkat
     */
    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class);
    }

    /**
     * Relasi ke tabel users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter transaksi pemasukan
     */
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    /**
     * Scope: Filter transaksi pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    /**
     * Scope: Filter berdasarkan tingkat
     */
    public function scopeTingkat($query, $tingkatId)
    {
        return $query->where('tingkat_id', $tingkatId);
    }

    /**
     * Scope: Filter berdasarkan rentang tanggal
     */
    public function scopeTanggalAntara($query, $dari, $sampai)
    {
        return $query->whereBetween('tanggal', [$dari, $sampai]);
    }

    /**
     * Scope: Filter transaksi uang jajan
     */
    public function scopeUangJajan($query)
    {
        return $query->whereHas('kategori', function ($q) {
            $q->where('is_uang_jajan', true);
        });
    }
}