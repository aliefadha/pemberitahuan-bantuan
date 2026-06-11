<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    protected $fillable = [
        'name',
        'jorong',
    ];

    /**
     * Users that belong to this kelompok.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the human-readable jorong label.
     */
    public function getJorongLabelAttribute(): string
    {
        return match ($this->jorong) {
            'padang_rantang' => 'Padang Rantang',
            'tanjung_pati' => 'Tanjung Pati',
            'koto_tuo' => 'Koto Tuo',
            'pulutan' => 'Pulutan',
            default => $this->jorong ?? '-',
        };
    }
}
