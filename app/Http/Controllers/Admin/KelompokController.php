<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelompok::withCount('users');
        if (auth()->user()->isKader()) {
            $query->whereKey(auth()->user()->kelompok_id);
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $kelompoks = $query->paginate(10)->appends($request->only('search'));

        return view('admin.kelompoks.index', compact('kelompoks'));
    }

    public function create()
    {
        abort_if(auth()->user()->isKader(), 403, 'Kader tidak dapat membuat kelompok.');
        return view('admin.kelompoks.create');
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->isKader(), 403, 'Kader tidak dapat membuat kelompok.');
        if (auth()->user()->isKader()) {
            $request->merge(['jorong' => auth()->user()->jorong]);
        }

        $jorongRules = ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'];
        if (auth()->user()->isKader()) {
            $jorongRules[] = function ($attribute, $value, $fail) {
                if ($value !== auth()->user()->jorong) {
                    $fail('Anda hanya dapat membuat kelompok di jorong Anda sendiri.');
                }
            };
        }

        $request->validate([
            'name'   => ['required', 'string', 'max:255', 'unique:kelompoks,name'],
            'jorong' => $jorongRules,
        ], [
            'name.unique' => 'Nama kelompok sudah digunakan.',
        ]);

        Kelompok::create([
            'name'   => $request->name,
            'jorong' => $request->jorong,
        ]);

        return redirect()->route('admin.kelompoks.index')->with('success', 'Kelompok berhasil dibuat.');
    }

    public function show(Kelompok $kelompok)
    {
        if (auth()->user()->isKader() && $kelompok->id !== auth()->user()->kelompok_id) {
            abort(403, 'Unauthorized action.');
        }

        $kelompok->load(['users' => function($query) {
            $query->orderBy('name');
        }]);

        return view('admin.kelompoks.show', compact('kelompok'));
    }

    public function edit(Kelompok $kelompok)
    {
        if (auth()->user()->isKader() && $kelompok->id !== auth()->user()->kelompok_id) {
            abort(403, 'Unauthorized action.');
        }

        $kelompok->load('users');
        
        $allUsersQuery = User::orderBy('name')
            ->where(function ($query) use ($kelompok) {
                $query->whereNull('kelompok_id')
                      ->orWhere('kelompok_id', $kelompok->id);
            });
        if (auth()->user()->isKader()) {
            $allUsersQuery->where('jorong', auth()->user()->jorong)
                ->where('role', 'peserta');
        }
        $allUsers = $allUsersQuery->get(['id', 'name', 'kelompok_id', 'role', 'jorong']);

        return view('admin.kelompoks.edit', compact('kelompok', 'allUsers'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        if (auth()->user()->isKader() && $kelompok->id !== auth()->user()->kelompok_id) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->isKader()) {
            $request->merge(['jorong' => auth()->user()->jorong]);
        }

        $jorongRules = ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'];
        if (auth()->user()->isKader()) {
            $jorongRules[] = function ($attribute, $value, $fail) {
                if ($value !== auth()->user()->jorong) {
                    $fail('Anda hanya dapat merubah kelompok di jorong Anda sendiri.');
                }
            };
        }

        $request->validate([
            'name'   => ['required', 'string', 'max:255', 'unique:kelompoks,name,' . $kelompok->id],
            'jorong' => $jorongRules,
        ], [
            'name.unique' => 'Nama kelompok sudah digunakan.',
        ]);

        DB::transaction(function () use ($request, $kelompok) {
            $kelompok->update([
                'name'   => $request->name,
                'jorong' => $request->jorong,
            ]);

            $submittedUserIds = $request->input('users', []);

            if (auth()->user()->isKader() && !empty($submittedUserIds)) {
                $count = User::whereIn('id', $submittedUserIds)
                    ->where(function ($query) use ($kelompok) {
                        $query->where('jorong', '!=', $kelompok->jorong)
                            ->orWhere('role', '!=', 'peserta')
                            ->orWhereNotNull('kelompok_id')
                            ->where('kelompok_id', '!=', $kelompok->id);
                    })
                    ->count();
                if ($count > 0) {
                    abort(403, 'Anda hanya dapat memasukkan user dari jorong Anda sendiri.');
                }
            }

            // Users currently in this kelompok but NOT in submitted list → nullify
            $usersInKelompok = User::where('kelompok_id', $kelompok->id)
                ->whereNotIn('id', $submittedUserIds);
            if (auth()->user()->isKader()) {
                $usersInKelompok->where('role', 'peserta');
            }
            $usersInKelompok->update(['kelompok_id' => null]);

            // Users in submitted list → assign to this kelompok
            if (!empty($submittedUserIds)) {
                $usersToAssign = User::whereIn('id', $submittedUserIds);
                if (auth()->user()->isKader()) {
                    $usersToAssign->where('role', 'peserta');
                }
                $usersToAssign->update(['kelompok_id' => $kelompok->id]);
            }
        });

        return redirect()->route('admin.kelompoks.index')->with('success', 'Kelompok berhasil diupdate.');
    }

    public function destroy(Kelompok $kelompok)
    {
        if (auth()->user()->isKader() && $kelompok->id !== auth()->user()->kelompok_id) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($kelompok) {
            // Nullify all users' kelompok_id first
            User::where('kelompok_id', $kelompok->id)->update(['kelompok_id' => null]);

            $kelompok->delete();
        });

        return redirect()->route('admin.kelompoks.index')->with('success', 'Kelompok berhasil dihapus.');
    }

    public function exportPdf()
    {
        $query = Kelompok::withCount('users');
        if (auth()->user()->isKader()) {
            $query->whereKey(auth()->user()->kelompok_id);
        }
        $kelompoks = $query->get();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.kelompoks.pdf.all', compact('kelompoks'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-daftar-kelompok-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportPdfDetail(Kelompok $kelompok)
    {
        if (auth()->user()->isKader() && $kelompok->id !== auth()->user()->kelompok_id) {
            abort(403, 'Unauthorized action.');
        }

        $kelompok->load(['users' => function($query) {
            $query->orderBy('name');
        }]);

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.kelompoks.pdf.detail', compact('kelompok'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-detail-kelompok-' . str($kelompok->name)->slug() . '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}
