<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKeluarga extends Model
{
    protected $table = 'anggota_keluarga';
    protected $fillable = [
        'user_id',
        'nama',
        'status_dalam_keluarga',
        'status_perkawinan',
        'jenis_kelamin',
        'tanggal_lahir',
        'pekerjaan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function statusOptions(): array
    {
        return [
            'suami' => 'Suami',
            'istri' => 'Istri',
            'anak' => 'Anak',
        ];
    }

    public static function maritalOptions(): array
    {
        return [
            'menikah' => 'Menikah',
            'belum_menikah' => 'Belum Menikah',
            'cerai' => 'Cerai',
        ];
    }

    public static function genderOptions(): array
    {
        return [
            'laki_laki' => 'Laki-laki',
            'perempuan' => 'Perempuan',
        ];
    }
}