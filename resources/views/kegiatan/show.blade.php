<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{ __('Detail Kegiatan') }}
            </h2>
            <a href="{{ route('kegiatan.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
        </div>
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
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $kegiatan->judul }}</h3>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $kegiatan->tanggal->format('l, d F Y H:i') }}
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
    </div>
</x-app-layout>
