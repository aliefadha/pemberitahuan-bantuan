<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Services\KegiatanResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    public function index()
    {
        $query = Kegiatan::query();

        if (auth()->check() && ! auth()->user()->isAdmin()) {
            $user = auth()->user();
            $query->where('jorong', $user->jorong)
                ->where(function ($query) use ($user) {
                    $query->whereNull('kelompok_id')
                        ->orWhere('kelompok_id', $user->kelompok_id);
                });
        }

        $kegiatans = $query->latest()->paginate(10);

        return view('kegiatan.index', compact('kegiatans'));
    }

    public function show(Kegiatan $kegiatan)
    {
        if ($kegiatan->kelompok_id && $kegiatan->kelompok_id !== auth()->user()->kelompok_id) {
            abort(403, 'Unauthorized action.');
        }

        $userResponse = $kegiatan->users()->where('user_id', auth()->id())->first();
        $status = $userResponse ? $userResponse->pivot->status : null;
        $alasan = $userResponse ? $userResponse->pivot->alasan : null;

        $userJorong = auth()->user()->jorong;
        $kelompoks = Kelompok::where('jorong', $userJorong)
            ->with(['users' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
        if ($kegiatan->kelompok_id) {
            $kelompoks = $kelompoks->where('id', $kegiatan->kelompok_id)->values();
        }

        $responses = DB::table('kegiatan_user')
            ->where('kegiatan_id', $kegiatan->id)
            ->pluck('status', 'user_id')
            ->toArray();

        return view('kegiatan.show', compact('kegiatan', 'status', 'alasan', 'kelompoks', 'responses'));
    }

    public function respond(
        Request $request,
        Kegiatan $kegiatan,
        KegiatanResponseService $responseService
    ) {
        $request->validate([
            'status' => 'required|in:bersedia,tidak_bersedia',
            'alasan' => 'required_if:status,tidak_bersedia|nullable|string',
        ]);

        $status = $request->status;
        $alasan = $status === 'tidak_bersedia' ? $request->alasan : null;

        $result = $responseService->submit(
            $kegiatan,
            auth()->user(),
            $status,
            $alasan
        );

        $message = $result === KegiatanResponseService::CREATED
            ? 'Tanggapan Anda telah disimpan.'
            : 'Tanggapan Anda telah diperbarui.';

        return redirect()->route('kegiatan.show', $kegiatan)->with('success', $message);
    }
}
