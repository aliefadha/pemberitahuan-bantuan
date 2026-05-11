<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\User;
use App\Notifications\KegiatanNotification;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::latest()->paginate(10);

        return view('admin.kegiatans.index', compact('kegiatans'));
    }

    public function create()
    {
        return view('admin.kegiatans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
        ]);

        $kegiatan = Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
        ]);

        $usersToNotify = User::where('id', '!=', auth()->id())
            ->whereNotNull('bio_data')
            ->where('bio_data', '!=', '[]')
            ->get();
        foreach ($usersToNotify as $user) {
            $user->notify(new KegiatanNotification($kegiatan, 'created'));
        }

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $pesertas = $kegiatan->users()->orderBy('name')->get();

        return view('admin.kegiatans.show', compact('kegiatan', 'pesertas'));
    }

    public function notify(Kegiatan $kegiatan)
    {
        $usersToNotify = User::where('id', '!=', auth()->id())
            ->whereNotNull('bio_data')
            ->where('bio_data', '!=', '[]')
            ->get();
        foreach ($usersToNotify as $user) {
            $user->notify(new KegiatanNotification($kegiatan, 'created'));
        }

        return back()->with('success', 'Notifikasi WhatsApp berhasil dikirim ulang ke semua pengguna.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatans.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
        ]);

        $kegiatan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
