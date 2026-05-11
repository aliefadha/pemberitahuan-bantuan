<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            {{ __('Data Bio') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Formulir Data Bio</h3>
                <p class="text-sm text-gray-500 mt-1">Isi data bio peserta dengan benar</p>
            </div>
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('bio.update') }}">
                    @csrf
                    @method('patch')

                    <div class="space-y-8">
                        @foreach($sections as $section)
                            <div class="border border-gray-200 rounded-lg p-6 space-y-6">
                                <h4 class="text-base font-semibold text-gray-800 mb-4">{{ $section['title'] }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($section['questions'] as $q)
                                        <div>
                                            <label for="bio_data_{{ $q['key'] }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $q['question'] }}
                                            </label>

                                            @if($q['type'] === 'integer')
                                                <input type="number"
                                                    id="bio_data_{{ $q['key'] }}"
                                                    name="bio_data[{{ $q['key'] }}]"
                                                    value="{{ old('bio_data.' . $q['key'], $bioData[$q['key']] ?? '') }}"
                                                    @isset($q['min']) min="{{ $q['min'] }}" @endisset
                                                    @isset($q['max']) max="{{ $q['max'] }}" @endisset
                                                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error("bio_data.{$q['key']}") border-red-500 @enderror"
                                                    placeholder="0">
                                            @elseif($q['type'] === 'boolean')
                                                <select id="bio_data_{{ $q['key'] }}"
                                                    name="bio_data[{{ $q['key'] }}]"
                                                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error("bio_data.{$q['key']}") border-red-500 @enderror">
                                                    <option value="">Pilih</option>
                                                    <option value="1" {{ old('bio_data.' . $q['key'], $bioData[$q['key']] ?? '') == '1' ? 'selected' : '' }}>Ya</option>
                                                    <option value="0" {{ old('bio_data.' . $q['key'], $bioData[$q['key']] ?? '') == '0' ? 'selected' : '' }}>Tidak</option>
                                                </select>
                                            @endif

                                            @error("bio_data.{$q['key']}")
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" class="w-full px-4 py-2 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition">
                            Simpan Data Bio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
