<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
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
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'alamat' => ['nullable', 'string', 'max:500'],
            'jorong' => ['nullable', 'string', 'in:padang_rantang,pulutan,koto_tuo,tanjung_pati'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'anggota_keluarga' => ['nullable', 'array', 'max:20'],
            'anggota_keluarga.*.nama' => ['required_with:anggota_keluarga', 'string', 'max:255'],
            'anggota_keluarga.*.status_dalam_keluarga' => ['required_with:anggota_keluarga', 'in:suami,istri,anak'],
            'anggota_keluarga.*.status_perkawinan' => ['required_with:anggota_keluarga', 'in:menikah,belum_menikah,cerai'],
            'anggota_keluarga.*.jenis_kelamin' => ['required_with:anggota_keluarga', 'in:laki_laki,perempuan'],
            'anggota_keluarga.*.tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'anggota_keluarga.*.pekerjaan' => ['nullable', 'string', 'max:255'],
        ]);

        $role = 'peserta';

        if ($request->user() && $request->user()->isAdmin() && $request->has('role')) {
            $request->validate(['role' => ['in:admin,peserta']]);
            $role = $request->role;
        }

        $user = DB::transaction(function () use ($validated, $role) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'alamat' => $validated['alamat'] ?? null,
                'jorong' => $validated['jorong'] ?? null,
                'no_telepon' => $validated['no_telepon'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $role,
            ]);

            if (! empty($validated['anggota_keluarga'])) {
                foreach ($validated['anggota_keluarga'] as $fm) {
                    $user->anggotaKeluarga()->create([
                        'nama' => $fm['nama'],
                        'status_dalam_keluarga' => $fm['status_dalam_keluarga'],
                        'status_perkawinan' => $fm['status_perkawinan'],
                        'jenis_kelamin' => $fm['jenis_kelamin'],
                        'tanggal_lahir' => $fm['tanggal_lahir'] ?? null,
                        'pekerjaan' => $fm['pekerjaan'] ?? null,
                    ]);
                }
            }

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}