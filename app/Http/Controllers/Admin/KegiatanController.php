<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\User;
use App\Notifications\KegiatanNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::latest();
        if (auth()->user()->isKader()) {
            $query->where('kelompok_id', auth()->user()->kelompok_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $kegiatans = $query->paginate(10)->appends($request->only('search'));

        return view('admin.kegiatans.index', compact('kegiatans'));
    }

    public function create()
    {
        $kelompoks = $this->availableKelompoks();

        return view('admin.kegiatans.create', compact('kelompoks'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isKader()) {
            $kelompok = $this->currentKelompok();
            $request->merge(['jorong' => $kelompok->jorong, 'kelompok_id' => $kelompok->id]);
        }

        $jorongRules = ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'];
        if (auth()->user()->isKader()) {
            $jorongRules[] = function ($attribute, $value, $fail) {
                if ($value !== auth()->user()->jorong) {
                    $fail('Anda hanya dapat membuat kegiatan di jorong Anda sendiri.');
                }
            };
        }

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
            'jorong' => $jorongRules,
            'kelompok_id' => $this->kelompokRules($request),
        ]);

        $kegiatan = Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jorong' => $request->jorong,
            'kelompok_id' => $request->kelompok_id,
        ]);

        $query = User::where('role', 'peserta')
            ->whereNotNull('no_telepon');

        if ($kegiatan->jorong) {
            $query->where('jorong', $kegiatan->jorong);
        }
        if ($kegiatan->kelompok_id) {
            $query->where('kelompok_id', $kegiatan->kelompok_id);
        }

        $usersToNotify = $query->get();
        foreach ($usersToNotify as $user) {
            $user->notify(new KegiatanNotification($kegiatan, 'created'));
        }

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);
        // Get all users in the activity's jorong (excluding admin)
        $pesertas = User::where('jorong', $kegiatan->jorong)
            ->whereNotIn('role', ['admin', 'kader'])
            ->with('kelompok')
            ->orderBy('name')
            ->get();
        if ($kegiatan->kelompok_id) {
            $pesertas = $pesertas->where('kelompok_id', $kegiatan->kelompok_id)->values();
        }

        // Get response statuses mapped by user_id
        $responses = DB::table('kegiatan_user')
            ->where('kegiatan_id', $kegiatan->id)
            ->get(['user_id', 'status', 'alasan', 'updated_at'])
            ->keyBy('user_id')
            ->toArray();

        return view('admin.kegiatans.show', compact('kegiatan', 'pesertas', 'responses'));
    }

    public function notify(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);
        $query = User::where('role', 'peserta')
            ->whereNotNull('no_telepon');

        if ($kegiatan->jorong) {
            $query->where('jorong', $kegiatan->jorong);
        }
        if ($kegiatan->kelompok_id) {
            $query->where('kelompok_id', $kegiatan->kelompok_id);
        }

        $usersToNotify = $query->get();
        foreach ($usersToNotify as $user) {
            $user->notify(new KegiatanNotification($kegiatan, 'created'));
        }

        return back()->with('success', 'Notifikasi WhatsApp berhasil dikirim ulang ke semua pengguna.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);
        $kelompoks = $this->availableKelompoks();
        return view('admin.kegiatans.edit', compact('kegiatan', 'kelompoks'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);

        if (auth()->user()->isKader()) {
            $kelompok = $this->currentKelompok();
            $request->merge(['jorong' => $kelompok->jorong, 'kelompok_id' => $kelompok->id]);
        }

        $jorongRules = ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'];
        if (auth()->user()->isKader()) {
            $jorongRules[] = function ($attribute, $value, $fail) {
                if ($value !== auth()->user()->jorong) {
                    $fail('Anda hanya dapat merubah kegiatan di jorong Anda sendiri.');
                }
            };
        }

        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
            'jorong' => $jorongRules,
            'kelompok_id' => $this->kelompokRules($request),
        ]);

        $kegiatan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jorong' => $request->jorong,
            'kelompok_id' => $request->kelompok_id,
        ]);

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);

        $kegiatan->delete();

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function exportPdf()
    {
        $query = Kegiatan::latest();
        if (auth()->user()->isKader()) {
            $query->where('kelompok_id', auth()->user()->kelompok_id);
        }
        $kegiatans = $query->get();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.kegiatans.pdf.all', compact('kegiatans'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-daftar-kegiatan-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportPdfDetail(Kegiatan $kegiatan)
    {
        $this->authorizeKegiatan($kegiatan);

        // Get all users in the activity's jorong (excluding admin)
        $pesertas = User::where('jorong', $kegiatan->jorong)
            ->whereNotIn('role', ['admin', 'kader'])
            ->with('kelompok')
            ->orderBy('name')
            ->get();
        if ($kegiatan->kelompok_id) {
            $pesertas = $pesertas->where('kelompok_id', $kegiatan->kelompok_id)->values();
        }

        // Get response statuses mapped by user_id
        $responses = DB::table('kegiatan_user')
            ->where('kegiatan_id', $kegiatan->id)
            ->get(['user_id', 'status', 'alasan', 'updated_at'])
            ->keyBy('user_id')
            ->toArray();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.kegiatans.pdf.detail', compact('kegiatan', 'pesertas', 'responses'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-kegiatan-' . str($kegiatan->judul)->slug() . '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    private function currentKelompok(): Kelompok
    {
        $kelompok = auth()->user()->kelompok;
        abort_unless($kelompok, 403, 'Kader harus tergabung dalam kelompok.');

        return $kelompok;
    }

    private function authorizeKegiatan(Kegiatan $kegiatan): void
    {
        if (auth()->user()->isKader() && $kegiatan->kelompok_id !== auth()->user()->kelompok_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function availableKelompoks()
    {
        $query = Kelompok::orderBy('jorong')->orderBy('name');
        if (auth()->user()->isKader()) {
            $query->whereKey(auth()->user()->kelompok_id);
        }

        return $query->get();
    }

    private function kelompokRules(Request $request): array
    {
        return [
            auth()->user()->isKader() ? 'required' : 'nullable',
            'exists:kelompoks,id',
            function ($attribute, $value, $fail) use ($request) {
                if (! $value) {
                    return;
                }

                $kelompok = Kelompok::find($value);
                if (! $kelompok || $kelompok->jorong !== $request->jorong) {
                    $fail('Kelompok harus berada di jorong yang sama dengan kegiatan.');
                }
            },
        ];
    }
}
