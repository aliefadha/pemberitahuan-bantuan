<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\User;
use App\Notifications\KegiatanResponseNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class GuestKegiatanController extends Controller
{
    public function show(Request $request, Kegiatan $kegiatan, User $user): View
    {
        $this->ensureEligible($kegiatan, $user);

        $userResponse = $kegiatan->users()->where('user_id', $user->id)->first();
        $status = $userResponse?->pivot->status;
        $alasan = $userResponse?->pivot->alasan;

        return view('kegiatan.guest-show', [
            'kegiatan' => $kegiatan,
            'recipient' => $user,
            'status' => $status,
            'alasan' => $alasan,
            'respondUrl' => $this->signedUrl('kegiatan.guest.respond', $request, $kegiatan, $user),
        ]);
    }

    public function respond(Request $request, Kegiatan $kegiatan, User $user): RedirectResponse
    {
        $this->ensureEligible($kegiatan, $user);

        $validated = $request->validate([
            'status' => ['required', 'in:bersedia,tidak_bersedia'],
            'alasan' => ['required_if:status,tidak_bersedia', 'nullable', 'string', 'max:1000'],
        ]);

        $status = $validated['status'];
        $alasan = $status === 'tidak_bersedia' ? $validated['alasan'] : null;

        $kegiatan->users()->syncWithoutDetaching([
            $user->id => [
                'status' => $status,
                'alasan' => $alasan,
            ],
        ]);

        User::whereIn('role', ['admin', 'kader'])->each(
            fn (User $admin) => $admin->notify(
                new KegiatanResponseNotification($kegiatan, $user, $status, $alasan)
            )
        );

        return redirect($this->signedUrl('kegiatan.guest.show', $request, $kegiatan, $user))
            ->with('success', 'Tanggapan Anda telah disimpan.');
    }

    private function ensureEligible(Kegiatan $kegiatan, User $user): void
    {
        $eligible = $user->isPeserta()
            && $user->jorong === $kegiatan->jorong
            && (! $kegiatan->kelompok_id || $user->kelompok_id === $kegiatan->kelompok_id);

        abort_unless($eligible, 403, 'Tautan ini tidak berlaku untuk penerima tersebut.');
    }

    private function signedUrl(string $route, Request $request, Kegiatan $kegiatan, User $user): string
    {
        $expires = (string) $request->query('expires', '');
        abort_unless(ctype_digit($expires), 403, 'Tautan konfirmasi tidak valid.');

        $expiresAt = Carbon::createFromTimestamp((int) $expires);

        return URL::temporarySignedRoute($route, $expiresAt, [
            'kegiatan' => $kegiatan,
            'user' => $user,
        ]);
    }
}
