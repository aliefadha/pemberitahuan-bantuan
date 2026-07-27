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
    public function index(Request $request)
    {
        $query = User::latest();

        if (auth()->user()->isKader()) {
            $query->where('kelompok_id', auth()->user()->kelompok_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->appends($request->only('search'));

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isKader()) {
            $kelompok = auth()->user()->kelompok;
            abort_unless($kelompok, 403, 'Kader harus tergabung dalam kelompok.');
            $request->merge(['jorong' => $kelompok->jorong, 'kelompok_id' => $kelompok->id]);
        }

        $rolesAllowed = auth()->user()->isKader() ? ['peserta'] : ['admin', 'peserta', 'kader'];
        $jorongRules = [$request->role === 'admin' ? 'nullable' : 'required', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'];
        if (auth()->user()->isKader()) {
            $jorongRules[] = function ($attribute, $value, $fail) {
                if ($value !== auth()->user()->jorong) {
                    $fail('Anda hanya dapat memilih jorong Anda sendiri.');
                }
            };
        }

        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'ends_with:@gmail.com', 'max:255', 'unique:users'],
            'role'       => ['required', Rule::in($rolesAllowed)],
            'no_telepon' => ['required', 'string', 'max:20', 'unique:users'],
            'jorong'     => $jorongRules,
            'kelompok_id' => ['nullable', 'exists:kelompoks,id'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'anggota_keluarga'                         => ['nullable', 'array'],
            'anggota_keluarga.*.nama'                  => ['required', 'string', 'max:255'],
            'anggota_keluarga.*.status_dalam_keluarga' => ['required', 'in:suami,istri,anak'],
            'anggota_keluarga.*.status_perkawinan'     => ['required', 'in:menikah,belum_menikah,cerai'],
            'anggota_keluarga.*.jenis_kelamin'         => ['required', 'in:laki_laki,perempuan'],
            'anggota_keluarga.*.tanggal_lahir'         => ['nullable', 'date'],
            'anggota_keluarga.*.pekerjaan'             => ['nullable', 'string', 'max:255'],
            'anggota_keluarga.*.status'                => ['nullable', 'in:meninggal,hamil'],
        ], [
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'email.ends_with' => 'format harus @gmail.com.',
            'email.unique'    => 'Email sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'role'       => $request->role,
                'no_telepon' => $request->no_telepon,
                'password'   => Hash::make($request->password),
                'jorong'     => $request->jorong,
                'kelompok_id' => $request->kelompok_id,
            ]);

            if (auth()->user()->role === 'admin') {
                foreach ($request->input('anggota_keluarga', []) as $anggota) {
                    $user->anggotaKeluarga()->create($anggota);
                }
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if (auth()->user()->isKader() && ($user->kelompok_id !== auth()->user()->kelompok_id || $user->role !== 'peserta')) {
            abort(403, 'Unauthorized action.');
        }

        $sections  = config('bio.sections');
        
        $kelompoksQuery = Kelompok::orderBy('jorong')->orderBy('name');
        if (auth()->user()->isKader()) {
            $kelompoksQuery->whereKey(auth()->user()->kelompok_id);
        }
        $kelompoks = $kelompoksQuery->get();

        return view('admin.users.edit', compact('user', 'sections', 'kelompoks'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->isKader() && ($user->kelompok_id !== auth()->user()->kelompok_id || $user->role !== 'peserta')) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->isKader()) {
            $kelompok = auth()->user()->kelompok;
            abort_unless($kelompok, 403, 'Kader harus tergabung dalam kelompok.');
            $request->merge(['jorong' => $kelompok->jorong, 'kelompok_id' => $kelompok->id]);
        }

        $rolesAllowed = auth()->user()->isKader() ? [$user->role] : ['admin', 'peserta', 'kader'];
        $jorongRules = [$request->role === 'admin' ? 'nullable' : 'required', 'in:padang_rantang,tanjung_pati,koto_tuo,pulutan'];
        if (auth()->user()->isKader()) {
            $jorongRules[] = function ($attribute, $value, $fail) {
                if ($value !== auth()->user()->jorong) {
                    $fail('Anda hanya dapat memilih jorong Anda sendiri.');
                }
            };
        }

        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'ends_with:@gmail.com', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'       => ['required', Rule::in($rolesAllowed)],
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
            'kelompok_id'                              => [
                'nullable',
                'exists:kelompoks,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->role !== 'admin') {
                        $kelompok = Kelompok::find($value);
                        if ($kelompok && $kelompok->jorong !== $request->jorong) {
                            $fail('Kelompok yang dipilih harus berada di jorong yang sama dengan user.');
                        }
                    }
                }
            ],
            'jorong'                                   => $jorongRules,
        ], [
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'email.ends_with' => 'format harus @gmail.com.',
            'email.unique'    => 'Email sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'name'        => $request->name,
                'email'       => $request->email,
                'role'        => $request->role,
                'no_telepon'  => $request->no_telepon,
                'kelompok_id' => $request->kelompok_id ?: null,
                'jorong'      => $request->jorong,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if (auth()->user()->role === 'admin') {
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
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->isKader() && ($user->kelompok_id !== auth()->user()->kelompok_id || $user->role !== 'peserta')) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
