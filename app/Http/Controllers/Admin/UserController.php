<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role'       => ['required', 'in:admin,peserta'],
            'no_telepon' => ['required', 'string', 'max:20', 'unique:users'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'anggota_keluarga'                         => ['nullable', 'array'],
            'anggota_keluarga.*.nama'                  => ['required', 'string', 'max:255'],
            'anggota_keluarga.*.status_dalam_keluarga' => ['required', 'in:suami,istri,anak'],
            'anggota_keluarga.*.status_perkawinan'     => ['required', 'in:menikah,belum_menikah,cerai'],
            'anggota_keluarga.*.jenis_kelamin'         => ['required', 'in:laki_laki,perempuan'],
            'anggota_keluarga.*.tanggal_lahir'         => ['nullable', 'date'],
            'anggota_keluarga.*.pekerjaan'             => ['nullable', 'string', 'max:255'],
            'anggota_keluarga.*.status'                => ['nullable', 'in:meninggal,hamil'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'role'       => $request->role,
                'no_telepon' => $request->no_telepon,
                'password'   => Hash::make($request->password),
            ]);

            foreach ($request->input('anggota_keluarga', []) as $anggota) {
                $user->anggotaKeluarga()->create($anggota);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $sections  = config('bio.sections');
        $kelompoks = Kelompok::orderBy('jorong')->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'sections', 'kelompoks'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'       => ['required', 'in:admin,peserta'],
            'no_telepon' => ['nullable', 'string', 'max:20', Rule::unique('users', 'no_telepon')->ignore($user->id)],
            'password'   => ['nullable', 'confirmed', Rules\Password::defaults()],
            'anggota_keluarga'                         => ['nullable', 'array'],
            'anggota_keluarga.*.nama'                  => ['required', 'string', 'max:255'],
            'anggota_keluarga.*.status_dalam_keluarga' => ['required', 'in:suami,istri,anak'],
            'anggota_keluarga.*.status_perkawinan'     => ['required', 'in:menikah,belum_menikah,cerai'],
            'anggota_keluarga.*.jenis_kelamin'         => ['required', 'in:laki_laki,perempuan'],
            'anggota_keluarga.*.tanggal_lahir'         => ['nullable', 'date'],
            'anggota_keluarga.*.pekerjaan'             => ['nullable', 'string', 'max:255'],
            'anggota_keluarga.*.status'                => ['nullable', 'in:meninggal,hamil'],
            'kelompok_id'                              => ['nullable', 'exists:kelompoks,id'],
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'name'        => $request->name,
                'email'       => $request->email,
                'role'        => $request->role,
                'no_telepon'  => $request->no_telepon,
                'kelompok_id' => $request->kelompok_id ?: null,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if ($request->has('bio_data')) {
                $sections = config('bio.sections');
                $rules    = [];

                foreach ($sections as $section) {
                    foreach ($section['questions'] as $q) {
                        $key = $q['key'];
                        if ($q['type'] === 'integer') {
                            $rules["bio_data.{$key}"] = ['nullable', 'integer'];
                        } elseif ($q['type'] === 'boolean') {
                            $rules["bio_data.{$key}"] = ['nullable', 'integer', 'in:0,1'];
                        }
                    }
                }

                $validatedBio = $request->validate($rules);
                $user->update(['bio_data' => $validatedBio['bio_data'] ?? []]);
            }

            // Sync anggota keluarga: replace all with submitted data
            $user->anggotaKeluarga()->delete();
            foreach ($request->input('anggota_keluarga', []) as $anggota) {
                $user->anggotaKeluarga()->create($anggota);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
