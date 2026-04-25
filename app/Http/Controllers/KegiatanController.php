<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::latest()->paginate(10);

        return view('kegiatan.index', compact('kegiatans'));
    }

    public function show(Kegiatan $kegiatan)
    {
        $userResponse = $kegiatan->users()->where('user_id', auth()->id())->first();
        $status = $userResponse ? $userResponse->pivot->status : null;

        return view('kegiatan.show', compact('kegiatan', 'status'));
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
