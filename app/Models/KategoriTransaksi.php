<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriTransaksi extends Model
{
    protected $table = 'kategori_transaksi';
    
    protected $fillable = [
        'nama',
        'jenis',
        'is_uang_jajan',
        'is_sistem',
    ];

    protected $casts = [
        'is_uang_jajan' => 'boolean',
        'is_sistem' => 'boolean',
    ];

    /**
     * Relasi ke tabel transaksi_kas
     */
    public function transaksiKas(): HasMany
    {
        return $this->hasMany(TransaksiKas::class);
    }

    /**
     * Scope: Filter kategori pemasukan
     */
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    /**
     * Scope: Filter kategori pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    /**
     * Scope: Filter kategori uang jajan
     */
    public function scopeUangJajan($query)
    {
        return $query->where('is_uang_jajan', true);
    }
}