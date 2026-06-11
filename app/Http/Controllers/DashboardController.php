<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Common stats: Kegiatan per Jorong and Kelompok per Jorong
        $jorongMap = [
            'padang_rantang' => 'Padang Rantang',
            'tanjung_pati'   => 'Tanjung Pati',
            'koto_tuo'       => 'Koto Tuo',
            'pulutan'        => 'Pulutan',
        ];

        $kegiatanRaw = Kegiatan::select('jorong', DB::raw('count(*) as total'))
            ->groupBy('jorong')
            ->get()
            ->pluck('total', 'jorong');

        $kegiatanPerJorong = [];
        foreach ($jorongMap as $key => $label) {
            $kegiatanPerJorong[$label] = $kegiatanRaw->get($key, 0);
        }

        $kelompokRaw = Kelompok::select('jorong', DB::raw('count(*) as total'))
            ->groupBy('jorong')
            ->get()
            ->pluck('total', 'jorong');

        $kelompokPerJorong = [];
        foreach ($jorongMap as $key => $label) {
            $kelompokPerJorong[$label] = $kelompokRaw->get($key, 0);
        }

        if ($user->isAdmin()) {
            $stats = [
                'totalPesertas' => User::where('role', 'peserta')->count(),
                'totalKegiatans' => Kegiatan::count(),
                'totalAnggota' => \App\Models\AnggotaKeluarga::count(),
                'totalHamil' => \App\Models\AnggotaKeluarga::where('status', 'hamil')->count(),
                'totalMeninggal' => \App\Models\AnggotaKeluarga::where('status', 'meninggal')->count(),
            ];

            $activeKegiatans = Kegiatan::where('tanggal', '>', now())->get();

            // Family stats grouped by Kelompok
            $kelompoks = Kelompok::with(['users.anggotaKeluarga'])->get();
            $familyStats = [];

            foreach ($kelompoks as $kelompok) {
                $hamil = 0;
                $meninggal = 0;
                $normal = 0;

                foreach ($kelompok->users as $u) {
                    foreach ($u->anggotaKeluarga as $anggota) {
                        if ($anggota->status === 'hamil') {
                            $hamil++;
                        } elseif ($anggota->status === 'meninggal') {
                            $meninggal++;
                        } else {
                            $normal++;
                        }
                    }
                }

                $familyStats[] = [
                    'name' => $kelompok->name,
                    'hamil' => $hamil,
                    'meninggal' => $meninggal,
                    'normal' => $normal,
                ];
            }

            // Unassigned users (Tanpa Kelompok)
            $unassignedUsers = User::whereNull('kelompok_id')->with('anggotaKeluarga')->get();
            $unassignedHamil = 0;
            $unassignedMeninggal = 0;
            $unassignedNormal = 0;

            foreach ($unassignedUsers as $u) {
                foreach ($u->anggotaKeluarga as $anggota) {
                    if ($anggota->status === 'hamil') {
                        $unassignedHamil++;
                    } elseif ($anggota->status === 'meninggal') {
                        $unassignedMeninggal++;
                    } else {
                        $unassignedNormal++;
                    }
                }
            }

            if ($unassignedHamil > 0 || $unassignedMeninggal > 0 || $unassignedNormal > 0) {
                $familyStats[] = [
                    'name' => 'Tanpa Kelompok',
                    'hamil' => $unassignedHamil,
                    'meninggal' => $unassignedMeninggal,
                    'normal' => $unassignedNormal,
                ];
            }

            return view('dashboard', compact('stats', 'activeKegiatans', 'kegiatanPerJorong', 'kelompokPerJorong', 'familyStats'));
        } else {
            // For regular user, filter active kegiatan by their jorong (peserta access)
            $activeKegiatans = Kegiatan::where('tanggal', '>', now())
                ->where('jorong', $user->jorong)
                ->get();

            // Family stats for user's own kelompok
            $kelompok = $user->kelompok;
            $familyStats = [
                'name' => $kelompok ? $kelompok->name : 'Pribadi',
                'hamil' => 0,
                'meninggal' => 0,
                'normal' => 0,
            ];

            if ($kelompok) {
                $kelompok->load('users.anggotaKeluarga');
                foreach ($kelompok->users as $u) {
                    foreach ($u->anggotaKeluarga as $anggota) {
                        if ($anggota->status === 'hamil') {
                            $familyStats['hamil']++;
                        } elseif ($anggota->status === 'meninggal') {
                            $familyStats['meninggal']++;
                        } else {
                            $familyStats['normal']++;
                        }
                    }
                }
            } else {
                $user->load('anggotaKeluarga');
                foreach ($user->anggotaKeluarga as $anggota) {
                    if ($anggota->status === 'hamil') {
                        $familyStats['hamil']++;
                    } elseif ($anggota->status === 'meninggal') {
                        $familyStats['meninggal']++;
                    } else {
                        $familyStats['normal']++;
                    }
                }
            }

            $totalAnggota = $familyStats['hamil'] + $familyStats['meninggal'] + $familyStats['normal'];
            $stats = [
                'totalKegiatans' => Kegiatan::where('jorong', $user->jorong)->count(),
                'totalAnggota' => $totalAnggota,
                'totalHamil' => $familyStats['hamil'],
                'totalMeninggal' => $familyStats['meninggal'],
            ];

            return view('dashboard', compact('stats', 'activeKegiatans', 'kegiatanPerJorong', 'kelompokPerJorong', 'familyStats'));
        }
    }
}
