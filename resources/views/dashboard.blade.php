<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Top Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
        @if(auth()->user()->isAdmin())
            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\User::count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Total Kelompok -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Kelompok</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Kelompok::count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        @else
            <!-- Jorong Anda -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jorong Anda</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ auth()->user()->jorong_label ?? '-' }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Kelompok Anda -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kelompok Anda</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ auth()->user()->kelompok ? auth()->user()->kelompok->name : '-' }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <!-- Total Kegiatan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Kegiatan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['totalKegiatans'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <!-- Anggota Hamil (Compared with All) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Keluarga Hamil</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['totalHamil'] }}</p>
                </div>
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500 leading-tight">
                {{ $stats['totalHamil'] }} dari {{ $stats['totalAnggota'] }} ({{ $stats['totalAnggota'] > 0 ? round(($stats['totalHamil'] / $stats['totalAnggota']) * 100, 1) : 0 }}%)
            </div>
        </div>

        <!-- Anggota Meninggal (Compared with All) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Keluarga Meninggal</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['totalMeninggal'] }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500 leading-tight">
                {{ $stats['totalMeninggal'] }} dari {{ $stats['totalAnggota'] }} ({{ $stats['totalAnggota'] > 0 ? round(($stats['totalMeninggal'] / $stats['totalAnggota']) * 100, 1) : 0 }}%)
            </div>
        </div>
        @endif
    </div>

    @if(auth()->user()->isAdmin())
    <!-- Main Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Kegiatan per Jorong -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Kegiatan per Jorong
            </h3>
            <div class="relative" style="height: 250px;">
                <canvas id="kegiatanJorongChart"></canvas>
            </div>
        </div>

        <!-- Kelompok per Jorong -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Kelompok per Jorong
            </h3>
            <div class="relative" style="height: 250px;">
                <canvas id="kelompokJorongChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- Active Kegiatan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Kegiatan yang Akan Datang
            </h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($activeKegiatans as $kegiatan)
                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50 transition">
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                            {{ $kegiatan->judul }}
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $kegiatan->jorong_label ?? '-' }}
                            </span>
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $kegiatan->tanggal->format('d M Y H:i') }} WIB</p>
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($kegiatan->deskripsi, 150) }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.kegiatans.show', $kegiatan) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                Detail Peserta
                            </a>
                        @else
                            <a href="{{ route('kegiatan.show', $kegiatan) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                Lihat & Beri Tanggapan
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 italic">Tidak ada kegiatan aktif dalam waktu dekat.</div>
            @endforelse
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- ChartJS Implementation -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Colors configuration
            const colors = {
                blue: '#3b82f6',
                emerald: '#10b981',
                amber: '#f59e0b',
                purple: '#8b5cf6',
                pink: '#ec4899',
                red: '#ef4444',
                gray: '#9ca3af'
            };

            // 1. Kegiatan per Jorong Chart
            const jorongLabels = @json(array_keys($kegiatanPerJorong));
            const kegiatanData = @json(array_values($kegiatanPerJorong));
            
            new Chart(document.getElementById('kegiatanJorongChart'), {
                type: 'bar',
                data: {
                    labels: jorongLabels,
                    datasets: [{
                        label: 'Kegiatan',
                        data: kegiatanData,
                        backgroundColor: colors.blue,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        }
                    }
                }
            });

            // 2. Kelompok per Jorong Chart
            const kelompokData = @json(array_values($kelompokPerJorong));
            
            new Chart(document.getElementById('kelompokJorongChart'), {
                type: 'bar',
                data: {
                    labels: jorongLabels,
                    datasets: [{
                        label: 'Kelompok',
                        data: kelompokData,
                        backgroundColor: colors.purple,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-app-layout>