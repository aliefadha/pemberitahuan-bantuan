<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            {{ __('Detail Kegiatan') }}
        </h2>
    </x-slot>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2 max-w-4xl mx-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-6">
                <div class="flex items-center justify-between mb-6">
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
                    <div>
                        <a href="{{ route('kegiatan.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-100 px-4 py-2 rounded-lg transition hover:bg-gray-200">
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="prose max-w-none text-gray-700">
                    <p class="whitespace-pre-wrap">{{ $kegiatan->deskripsi }}</p>
                </div>
            </div>
            
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Kehadiran</h4>
                
                @if($status)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Status Anda saat ini:</p>
                        @if($status === 'bersedia')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Bersedia Hadir
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tidak Bersedia
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Anda dapat mengubah status kehadiran Anda menggunakan tombol di bawah ini:</p>
                @else
                    <p class="text-sm text-gray-500 mb-4">Silakan konfirmasi apakah Anda bersedia hadir pada kegiatan ini.</p>
                @endif

                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('kegiatan.respond', $kegiatan) }}">
                        @csrf
                        <input type="hidden" name="status" value="bersedia">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-100 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Bersedia
                        </button>
                    </form>

                    <form method="POST" action="{{ route('kegiatan.respond', $kegiatan) }}">
                        @csrf
                        <input type="hidden" name="status" value="tidak_bersedia">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-100 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tidak Bersedia
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kelompok Responses --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Tanggapan Berdasarkan Kelompok
                </h4>

                @php
                    $userKelompokId = auth()->user()->kelompok_id;
                    $filteredKelompoks = $kelompoks->filter(fn($k) => $k->id === $userKelompokId);
                @endphp

                <div class="grid grid-cols-1 max-w-2xl mx-auto gap-6">
                    @forelse($filteredKelompoks as $kelompok)
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm flex flex-col">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                <span class="font-semibold text-gray-700">{{ $kelompok->name }}</span>
                                <span class="text-xs font-medium bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full">
                                    {{ $kelompok->users->count() }} Anggota
                                </span>
                            </div>
                            <div class="p-4 bg-white divide-y divide-gray-100 flex-1">
                                @forelse($kelompok->users as $member)
                                    @php
                                        $memberStatus = $responses[$member->id] ?? 'belum_menanggapi';
                                    @endphp
                                    <div class="py-2 flex justify-between items-center gap-4">
                                        <span class="text-sm text-gray-800 {{ $member->id === auth()->id() ? 'font-bold' : '' }}">
                                            {{ $member->name }} {{ $member->id === auth()->id() ? '(Anda)' : '' }}
                                        </span>
                                        <div>
                                            @if($memberStatus === 'bersedia')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                    Bersedia
                                                </span>
                                            @elseif($memberStatus === 'tidak_bersedia')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    Tidak Bersedia
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                    Belum Merespons
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada anggota di kelompok ini.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full border border-dashed border-gray-300 rounded-xl p-8 text-center text-gray-500">
                            @if(auth()->user()->kelompok_id)
                                Tidak ada data kelompok Anda.
                            @else
                                Anda belum tergabung dalam kelompok jorong. Silakan <a href="{{ route('bio.edit') }}" class="text-purple-600 hover:underline font-semibold">lengkapi data bio Anda</a> untuk memilih kelompok.
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
