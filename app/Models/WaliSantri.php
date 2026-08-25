<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaliSantri extends Model
{
    protected $table = 'wali_santri';
    
    protected $fillable = [
        'user_id',
        'santri_id',
        'hubungan',
    ];

    /**
     * Relasi ke tabel users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel santri
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Scope: Filter berdasarkan user (ortu)
     */
    public function scopeOrtu($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}