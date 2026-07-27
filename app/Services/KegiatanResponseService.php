<?php

namespace App\Services;

use App\Models\Kegiatan;
use App\Models\User;
use App\Notifications\KegiatanResponseNotification;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class KegiatanResponseService
{
    public const CREATED = 'created';

    public const UPDATED = 'updated';

    public function submit(
        Kegiatan $kegiatan,
        User $user,
        string $status,
        ?string $alasan = null
    ): string {
        if (! in_array($status, ['bersedia', 'tidak_bersedia'], true)) {
            throw new InvalidArgumentException('Status tanggapan tidak valid.');
        }

        $alasan = $status === 'tidak_bersedia' ? trim((string) $alasan) : null;

        if ($status === 'tidak_bersedia' && $alasan === '') {
            throw new InvalidArgumentException('Alasan wajib diisi.');
        }

        $result = DB::transaction(function () use ($kegiatan, $user, $status, $alasan) {
            $exists = DB::table('kegiatan_user')
                ->where('kegiatan_id', $kegiatan->id)
                ->where('user_id', $user->id)
                ->exists();

            $kegiatan->users()->syncWithoutDetaching([
                $user->id => [
                    'status' => $status,
                    'alasan' => $alasan,
                ],
            ]);

            return $exists ? self::UPDATED : self::CREATED;
        });

        $admins = User::where('role', 'admin')
            ->orWhere(function ($query) use ($kegiatan) {
                $query->where('role', 'kader');
                if ($kegiatan->kelompok_id) {
                    $query->where('kelompok_id', $kegiatan->kelompok_id);
                }
            })
            ->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new KegiatanResponseNotification($kegiatan, $user, $status, $alasan)
            );
        }

        return $result;
    }
}
