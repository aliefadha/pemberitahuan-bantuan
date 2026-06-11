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
    public function index()
    {
        $kelompoks = Kelompok::withCount('users')->paginate(10);

        return view('admin.kelompoks.index', compact('kelompoks'));
    }

    public function create()
    {
        return view('admin.kelompoks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'jorong' => ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'],
        ]);

        Kelompok::create([
            'name'   => $request->name,
            'jorong' => $request->jorong,
        ]);

        return redirect()->route('admin.kelompoks.index')->with('success', 'Kelompok berhasil dibuat.');
    }

    public function edit(Kelompok $kelompok)
    {
        $kelompok->load('users');
        $allUsers = User::orderBy('name')->get(['id', 'name', 'kelompok_id', 'role', 'jorong']);

        return view('admin.kelompoks.edit', compact('kelompok', 'allUsers'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'jorong' => ['required', 'string', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'],
        ]);

        DB::transaction(function () use ($request, $kelompok) {
            $kelompok->update([
                'name'   => $request->name,
                'jorong' => $request->jorong,
            ]);

            $submittedUserIds = $request->input('users', []);

            // Users currently in this kelompok but NOT in submitted list → nullify
            User::where('kelompok_id', $kelompok->id)
                ->whereNotIn('id', $submittedUserIds)
                ->update(['kelompok_id' => null]);

            // Users in submitted list → assign to this kelompok
            if (!empty($submittedUserIds)) {
                User::whereIn('id', $submittedUserIds)
                    ->update(['kelompok_id' => $kelompok->id]);
            }
        });

        return redirect()->route('admin.kelompoks.index')->with('success', 'Kelompok berhasil diupdate.');
    }

    public function destroy(Kelompok $kelompok)
    {
        DB::transaction(function () use ($kelompok) {
            // Nullify all users' kelompok_id first
            User::where('kelompok_id', $kelompok->id)->update(['kelompok_id' => null]);

            $kelompok->delete();
        });

        return redirect()->route('admin.kelompoks.index')->with('success', 'Kelompok berhasil dihapus.');
    }

    public function exportPdf()
    {
        $kelompoks = Kelompok::withCount('users')->get();

        Carbon::setLocale('id');

        $pdf = Pdf::loadView('admin.kelompoks.pdf.all', compact('kelompoks'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-daftar-kelompok-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportPdfDetail(Kelompok $kelompok)
    {
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
