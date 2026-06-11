<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $kelompoks = Kelompok::orderBy('jorong')->orderBy('name')->get();

        return view('auth.register', compact('kelompoks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'jorong'     => ['nullable', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'],
            'kelompok_id'                               => ['nullable', 'exists:kelompoks,id'],
            'anggota_keluarga'                          => ['nullable', 'array'],
            'anggota_keluarga.*.nama'                   => ['required', 'string', 'max:255'],
            'anggota_keluarga.*.status_dalam_keluarga'  => ['required', 'in:suami,istri,anak'],
            'anggota_keluarga.*.status_perkawinan'      => ['required', 'in:menikah,belum_menikah,cerai'],
            'anggota_keluarga.*.jenis_kelamin'          => ['required', 'in:laki_laki,perempuan'],
            'anggota_keluarga.*.tanggal_lahir'          => ['nullable', 'date'],
            'anggota_keluarga.*.pekerjaan'              => ['nullable', 'string', 'max:255'],
            'anggota_keluarga.*.status'                 => ['nullable', 'in:meninggal,hamil'],
        ]);

        $role = 'peserta';

        if ($request->user() && $request->user()->isAdmin() && $request->has('role')) {
            $request->validate(['role' => ['in:admin,peserta']]);
            $role = $request->role;
        }

        $user = DB::transaction(function () use ($validated, $role, $request) {
            $user = User::create([
                'name'        => $validated['name'],
                'email'       => $validated['email'],
                'no_telepon'  => $validated['no_telepon'] ?? null,
                'password'    => Hash::make($validated['password']),
                'role'        => $role,
                'jorong'      => $validated['jorong'] ?? null,
                'kelompok_id' => $validated['kelompok_id'] ?? null,
            ]);

            foreach ($validated['anggota_keluarga'] ?? [] as $anggota) {
                $user->anggotaKeluarga()->create($anggota);
            }

            return $user;
        });

        event(new Registered($user));

        return redirect(route('login', absolute: false))
            ->with('status', 'Akun berhasil dibuat! Silakan masuk.');
    }
}