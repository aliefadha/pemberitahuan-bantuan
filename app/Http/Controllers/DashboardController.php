<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'totalPesertas' => User::where('role', 'peserta')->count(),
                'totalKegiatans' => Kegiatan::count(),
            ];

            $activeKegiatans = Kegiatan::where('tanggal', '>', now())->get();

            return view('dashboard', compact('stats', 'activeKegiatans'));
        } else {
            $stats = [
                'totalKegiatans' => Kegiatan::count(),
            ];

            $activeKegiatans = Kegiatan::where('tanggal', '>', now())->get();

            return view('dashboard', compact('stats', 'activeKegiatans'));
        }
    }
}
