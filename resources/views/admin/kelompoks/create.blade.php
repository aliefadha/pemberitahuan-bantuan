<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Tambah Kelompok') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Form Tambah Kelompok</h3>
                <a href="{{ route('admin.kelompoks.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('admin.kelompoks.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelompok <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jorong" class="block text-sm font-medium text-gray-700 mb-1">Jorong <span class="text-red-500">*</span></label>
                        <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('jorong') border-red-500 @enderror" id="jorong" name="jorong" required>
                            <option value="">-- Pilih Jorong --</option>
                            <option value="padang_rantang" {{ old('jorong') == 'padang_rantang' ? 'selected' : '' }}>Padang Rantang</option>
                            <option value="tanjung_pati"   {{ old('jorong') == 'tanjung_pati'   ? 'selected' : '' }}>Tanjung Pati</option>
                            <option value="koto_tuo"       {{ old('jorong') == 'koto_tuo'       ? 'selected' : '' }}>Koto Tuo</option>
                            <option value="pulutan"        {{ old('jorong') == 'pulutan'        ? 'selected' : '' }}>Pulutan</option>
                        </select>
                        @error('jorong')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                            Simpan
                        </button>
                        <a href="{{ route('admin.kelompoks.index') }}" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
