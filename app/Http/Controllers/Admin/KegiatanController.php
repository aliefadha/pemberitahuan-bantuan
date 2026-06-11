<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\User;
use App\Notifications\KegiatanNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'jorong' => ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'],
        ]);

        $kegiatan = Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jorong' => $request->jorong,
        ]);

        $query = User::where('id', '!=', auth()->id())
            ->whereNotNull('bio_data')
            ->where('bio_data', '!=', '[]');

        if ($kegiatan->jorong) {
            $query->where('jorong', $kegiatan->jorong);
        }

        $usersToNotify = $query->get();
        foreach ($usersToNotify as $user) {
            $user->notify(new KegiatanNotification($kegiatan, 'created'));
        }

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show(Kegiatan $kegiatan)
    {
        // Get all users in the activity's jorong (excluding admin)
        $pesertas = User::where('jorong', $kegiatan->jorong)
            ->where('role', '!=', 'admin')
            ->with('kelompok')
            ->orderBy('name')
            ->get();

        // Get response statuses mapped by user_id
        $responses = DB::table('kegiatan_user')
            ->where('kegiatan_id', $kegiatan->id)
            ->get(['user_id', 'status', 'updated_at'])
            ->keyBy('user_id')
            ->toArray();

        return view('admin.kegiatans.show', compact('kegiatan', 'pesertas', 'responses'));
    }

    public function notify(Kegiatan $kegiatan)
    {
        $query = User::where('id', '!=', auth()->id())
            ->whereNotNull('bio_data')
            ->where('bio_data', '!=', '[]');

        if ($kegiatan->jorong) {
            $query->where('jorong', $kegiatan->jorong);
        }

        $usersToNotify = $query->get();
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
            'jorong' => ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'],
        ]);

        $kegiatan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jorong' => $request->jorong,
        ]);

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function exportPdf()
    {
        $kegiatans = Kegiatan::latest()->get();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.kegiatans.pdf.all', compact('kegiatans'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-daftar-kegiatan-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportPdfDetail(Kegiatan $kegiatan)
    {
        // Get all users in the activity's jorong (excluding admin)
        $pesertas = User::where('jorong', $kegiatan->jorong)
            ->where('role', '!=', 'admin')
            ->with('kelompok')
            ->orderBy('name')
            ->get();

        // Get response statuses mapped by user_id
        $responses = DB::table('kegiatan_user')
            ->where('kegiatan_id', $kegiatan->id)
            ->get(['user_id', 'status', 'updated_at'])
            ->keyBy('user_id')
            ->toArray();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.kegiatans.pdf.detail', compact('kegiatan', 'pesertas', 'responses'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-kegiatan-' . str($kegiatan->judul)->slug() . '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}
