<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BioController extends Controller
{
    public function show(): View
    {
        $sections = config('bio.sections');
        $user = auth()->user();

        return view('bio.show', [
            'sections' => $sections,
            'bioData' => $user->bio_data ?? [],
            'user' => $user,
        ]);
    }

    public function edit(): View
    {
        $sections = config('bio.sections');
        $user = auth()->user();
        $kelompoks = \App\Models\Kelompok::orderBy('jorong')->orderBy('name')->get();

        return view('bio.edit', [
            'sections' => $sections,
            'bioData' => $user->bio_data ?? [],
            'user' => $user,
            'kelompoks' => $kelompoks,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $sections = config('bio.sections');
        $rules = [
            'alamat'                                    => ['nullable', 'string', 'max:500'],
            'jorong'                                    => ['required', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'],
            'kelompok_id'                               => ['required', 'exists:kelompoks,id'],
            'anggota_keluarga'                          => ['nullable', 'array'],
            'anggota_keluarga.*.nama'                   => ['required', 'string', 'max:255'],
            'anggota_keluarga.*.status_dalam_keluarga'  => ['required', 'in:suami,istri,anak'],
            'anggota_keluarga.*.status_perkawinan'      => ['required', 'in:menikah,belum_menikah,cerai'],
            'anggota_keluarga.*.jenis_kelamin'          => ['required', 'in:laki_laki,perempuan'],
            'anggota_keluarga.*.tanggal_lahir'          => ['nullable', 'date'],
            'anggota_keluarga.*.pekerjaan'              => ['nullable', 'string', 'max:255'],
            'anggota_keluarga.*.status'                 => ['nullable', 'in:meninggal,hamil'],
        ];

        foreach ($sections as $section) {
            foreach ($section['questions'] as $q) {
                $key = $q['key'];
                if ($q['type'] === 'integer') {
                    $rules["bio_data.{$key}"] = ['nullable', 'integer'];
                    if (isset($q['min'])) {
                        $rules["bio_data.{$key}"][] = "min:{$q['min']}";
                    }
                    if (isset($q['max'])) {
                        $rules["bio_data.{$key}"][] = "max:{$q['max']}";
                    }
                } elseif ($q['type'] === 'boolean') {
                    $rules["bio_data.{$key}"] = ['nullable', 'integer', 'in:0,1'];
                }
            }
        }

        $validated = $request->validate($rules);

        $user = $request->user();

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $validated, $request) {
            $newJorong = $validated['jorong'] ?? null;
            $kelompokId = $validated['kelompok_id'] ?? null;

            if ($kelompokId) {
                $kelompok = \App\Models\Kelompok::find($kelompokId);
                if (!$kelompok || $kelompok->jorong !== $newJorong) {
                    $kelompokId = null;
                }
            }

            $user->alamat = $validated['alamat'] ?? null;
            $user->jorong = $newJorong;
            $user->kelompok_id = $kelompokId;
            $user->bio_data = $validated['bio_data'] ?? [];
            $user->save();

            // Sync family members
            $user->anggotaKeluarga()->delete();
            foreach ($request->input('anggota_keluarga', []) as $anggota) {
                $user->anggotaKeluarga()->create($anggota);
            }
        });

        return redirect()->route('bio.edit')->with('success', 'Bio berhasil disimpan.');
    }
}
