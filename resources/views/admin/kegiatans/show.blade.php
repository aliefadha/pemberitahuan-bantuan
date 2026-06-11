<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            {{ __('Daftar Peserta Kegiatan') }}
        </h2>
    </x-slot>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1 flex items-center gap-3">
                    {{ $kegiatan->judul }}
                    @if($kegiatan->jorong)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            {{ $kegiatan->jorong_label }}
                        </span>
                    @else
                        <span class="text-gray-400 text-sm font-normal">-</span>
                    @endif
                </h3>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $kegiatan->tanggal->format('l, d F Y H:i') }}
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('admin.kegiatans.notify', $kegiatan) }}" class="inline" onsubmit="return confirm('Kirim notifikasi WhatsApp ke semua pengguna (kecuali admin) tentang kegiatan ini?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9 2zm0 0v-8"/>
                        </svg>
                        Kirim Ulang WA
                    </button>
                </form>
                <a href="{{ route('admin.kegiatans.exportPdfDetail', $kegiatan) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition" title="Export detail kegiatan ini ke PDF">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.kegiatans.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-100 px-4 py-2 rounded-lg transition hover:bg-gray-200">
                    Kembali
                </a>
            </div>
        </div>
        <p class="text-gray-700 mt-2">{{ $kegiatan->deskripsi }}</p>
    </div>

    @php
        $groupedPesertas = $pesertas->groupBy(function($user) {
            return $user->kelompok ? $user->kelompok->name : 'Tanpa Kelompok';
        })->sortBy(function($group, $key) {
            return $key === 'Tanpa Kelompok' ? 'zzzzzzz' : $key;
        });
    @endphp

    <div class="space-y-6">
        @forelse($groupedPesertas as $kelompokName => $members)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $kelompokName }}</h3>
                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-gray-200 text-gray-700 rounded-full">
                        {{ $members->count() }} Orang
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-3" style="width: 50px;">No</th>
                                <th class="px-6 py-3">Nama Lengkap</th>
                                <th class="px-6 py-3">Nomor WhatsApp</th>
                                <th class="px-6 py-3">Status Kehadiran</th>
                                <th class="px-6 py-3">Waktu Tanggapan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($members as $index => $peserta)
                            @php
                                $response = $responses[$peserta->id] ?? null;
                                $status = $response ? $response->status : 'belum_menanggapi';
                                $updatedAt = $response ? \Carbon\Carbon::parse($response->updated_at) : null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $peserta->name }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    @if($peserta->no_telepon)
                                        <a href="https://wa.me/{{ $peserta->whatsapp_number }}" target="_blank" class="text-green-600 hover:text-green-800 hover:underline">
                                            {{ $peserta->no_telepon }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($status === 'bersedia')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Bersedia
                                        </span>
                                    @elseif($status === 'tidak_bersedia')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Tidak Bersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Belum Menanggapi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $updatedAt ? $updatedAt->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
                Tidak ada data peserta di jorong ini.
            </div>
        @endforelse
    </div>
</x-app-layout>
