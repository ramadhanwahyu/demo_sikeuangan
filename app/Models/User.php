<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'tingkat_id',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel tingkat (untuk bendahara)
     */
    public function tingkat(): BelongsTo
    {
        return $this->belongsTo(Tingkat::class);
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
     * Helper: Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper: Cek apakah user adalah bendahara
     */
    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    /**
     * Helper: Cek apakah user adalah pimpinan
     */
    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    /**
     * Helper: Cek apakah user adalah orang tua
     */
    public function isOrtu(): bool
    {
        return $this->role === 'ortu';
    }
}