<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('Edit Kegiatan') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Form Edit Kegiatan</h3>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.kegiatans.exportPdfDetail', $kegiatan) }}" class="inline-flex items-center gap-1 text-sm font-medium text-red-600 hover:text-red-900" title="Export detail kegiatan ini ke PDF">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('admin.kegiatans.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
                </div>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('admin.kegiatans.update', $kegiatan) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('judul') border-red-500 @enderror" id="judul" name="judul" value="{{ old('judul', $kegiatan->judul) }}" required autofocus>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('deskripsi') border-red-500 @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan</label>
                        <input type="datetime-local" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('tanggal') border-red-500 @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d\TH:i')) }}" required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jorong" class="block text-sm font-medium text-gray-700 mb-1">Jorong <span class="text-red-500">*</span></label>
                        <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('jorong') border-red-500 @enderror" id="jorong" name="jorong" required>
                            <option value="">-- Pilih Jorong --</option>
                            <option value="padang_rantang" {{ old('jorong', $kegiatan->jorong) == 'padang_rantang' ? 'selected' : '' }}>Padang Rantang</option>
                            <option value="tanjung_pati"   {{ old('jorong', $kegiatan->jorong) == 'tanjung_pati'   ? 'selected' : '' }}>Tanjung Pati</option>
                            <option value="koto_tuo"       {{ old('jorong', $kegiatan->jorong) == 'koto_tuo'       ? 'selected' : '' }}>Koto Tuo</option>
                            <option value="pulutan"        {{ old('jorong', $kegiatan->jorong) == 'pulutan'        ? 'selected' : '' }}>Pulutan</option>
                        </select>
                        @error('jorong')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>