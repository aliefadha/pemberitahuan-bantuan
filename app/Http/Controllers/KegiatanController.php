<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    public function index()
    {
        $query = Kegiatan::query();

        if (auth()->check() && !auth()->user()->isAdmin()) {
            $userJorong = auth()->user()->jorong;
            $query->where('jorong', $userJorong);
        }

        $kegiatans = $query->latest()->paginate(10);

        return view('kegiatan.index', compact('kegiatans'));
    }

    public function show(Kegiatan $kegiatan)
    {
        $userResponse = $kegiatan->users()->where('user_id', auth()->id())->first();
        $status = $userResponse ? $userResponse->pivot->status : null;

        $userJorong = auth()->user()->jorong;
        $kelompoks = Kelompok::where('jorong', $userJorong)
            ->with(['users' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $responses = DB::table('kegiatan_user')
            ->where('kegiatan_id', $kegiatan->id)
            ->pluck('status', 'user_id')
            ->toArray();

        return view('kegiatan.show', compact('kegiatan', 'status', 'kelompoks', 'responses'));
    }

    public function respond(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'status' => 'required|in:bersedia,tidak_bersedia',
        ]);

        $kegiatan->users()->syncWithoutDetaching([
            auth()->id() => ['status' => $request->status],
        ]);

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\KegiatanResponseNotification($kegiatan, auth()->user(), $request->status));
        }

        return redirect()->route('kegiatan.show', $kegiatan)->with('success', 'Tanggapan Anda telah disimpan.');
    }
}
