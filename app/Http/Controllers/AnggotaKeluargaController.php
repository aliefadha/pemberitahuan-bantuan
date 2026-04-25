<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggotaKeluarga;

class AnggotaKeluargaController extends Controller
{
    public function create()
    {
        return view('profile.anggota-keluarga-create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->anggotaKeluarga()->count() >= 20) {
            return redirect()->back()->withErrors(['error' => 'Maksimal 20 anggota keluarga.']);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'status_dalam_keluarga' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki_laki,perempuan',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required|string|max:255',
        ]);

        auth()->user()->anggotaKeluarga()->create($validated);

        return redirect()->route('profile.edit')->with('status', 'Anggota keluarga berhasil ditambahkan.');
    }
    public function edit(AnggotaKeluarga $anggotaKeluarga)
    {
        if ($anggotaKeluarga->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('profile.anggota-keluarga-edit', compact('anggotaKeluarga'));
    }

    public function update(Request $request, AnggotaKeluarga $anggotaKeluarga)
    {
        if ($anggotaKeluarga->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'status_dalam_keluarga' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki_laki,perempuan',
            'tanggal_lahir' => 'required|date',
            'pekerjaan' => 'required|string|max:255',
        ]);

        $anggotaKeluarga->update($validated);

        return redirect()->route('profile.edit')->with('status', 'Anggota keluarga berhasil diperbarui.');
    }

    public function destroy(AnggotaKeluarga $anggotaKeluarga)
    {
        if ($anggotaKeluarga->user_id !== auth()->id()) {
            abort(403);
        }

        $anggotaKeluarga->delete();

        return redirect()->route('profile.edit')->with('status', 'Anggota keluarga berhasil dihapus.');
    }
}
