<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kegiatan extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal',
        'jorong',
        'kelompok_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'alasan')->withTimestamps();
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    /**
     * Get the human-readable jorong label.
     */
    public function getJorongLabelAttribute(): ?string
    {
        return match ($this->jorong) {
            'padang_rantang' => 'Padang Rantang',
            'tanjung_pati' => 'Tanjung Pati',
            'koto_tuo' => 'Koto Tuo',
            'pulutan' => 'Pulutan',
            default => $this->jorong,
        };
    }
}
