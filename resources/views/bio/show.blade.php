<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl">

        {{-- Header Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
                    @if($user->alamat || $user->jorong)
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ collect([$user->jorong, $user->alamat])->filter()->implode(', ') }}
                        </p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $totalFields = collect($sections)->sum(fn($s) => count($s['questions']));
                        $filledFields = collect($sections)->sum(function($s) use ($bioData) {
                            return collect($s['questions'])->filter(fn($q) => isset($bioData[$q['key']]) && $bioData[$q['key']] !== '')->count();
                        });
                        $percent = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
                    @endphp
                    <div class="text-right">
                        <p class="text-xs text-gray-500 mb-1">Kelengkapan Bio</p>
                        <div class="flex items-center gap-2">
                            <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-500
                                    {{ $percent === 100 ? 'bg-green-500' : ($percent >= 50 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                    style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="text-sm font-semibold
                                {{ $percent === 100 ? 'text-green-600' : ($percent >= 50 ? 'text-yellow-600' : 'text-red-500') }}">
                                {{ $percent }}%
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('bio.edit') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Bio
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(empty($bioData))
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 flex items-start gap-4">
                <svg class="w-6 h-6 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <div>
                    <p class="font-semibold text-yellow-800">Data Bio Belum Diisi</p>
                    <p class="text-sm text-yellow-700 mt-1">Anda belum mengisi data bio. Silakan lengkapi data bio Anda.</p>
                    <a href="{{ route('bio.edit') }}" class="inline-block mt-3 px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition">
                        Isi Data Bio Sekarang
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($sections as $section)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ $section['title'] }}</h4>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($section['questions'] as $q)
                                    @php
                                        $value = $bioData[$q['key']] ?? null;
                                        $isFilled = isset($bioData[$q['key']]) && $bioData[$q['key']] !== '';
                                    @endphp
                                    <div class="flex items-start gap-3 p-3 rounded-lg {{ $isFilled ? 'bg-gray-50' : 'bg-red-50' }}">
                                        <div class="flex-shrink-0 mt-0.5">
                                            @if($isFilled)
                                                <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500 leading-tight">{{ $q['question'] }}</p>
                                            <p class="text-sm font-semibold text-gray-800 mt-0.5">
                                                @if(!$isFilled)
                                                    <span class="text-red-400 font-normal italic">Belum diisi</span>
                                                @elseif($q['type'] === 'boolean')
                                                    <span class="{{ $value == '1' ? 'text-green-700' : 'text-red-600' }}">
                                                        {{ $value == '1' ? 'Ya' : 'Tidak' }}
                                                    </span>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
