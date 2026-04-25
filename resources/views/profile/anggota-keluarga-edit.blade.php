<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Edit Anggota Keluarga
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Ubah Data Anggota Keluarga</h3>
                <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('anggota-keluarga.update', $anggotaKeluarga->id) }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $anggotaKeluarga->nama) }}" required
                            class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status_dalam_keluarga" class="block text-sm font-medium text-gray-700 mb-1">Status dalam Keluarga</label>
                            <select id="status_dalam_keluarga" name="status_dalam_keluarga" required
                                class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('status_dalam_keluarga') border-red-500 @enderror">
                                <option value="suami" {{ old('status_dalam_keluarga', $anggotaKeluarga->status_dalam_keluarga) == 'suami' ? 'selected' : '' }}>Suami</option>
                                <option value="istri" {{ old('status_dalam_keluarga', $anggotaKeluarga->status_dalam_keluarga) == 'istri' ? 'selected' : '' }}>Istri</option>
                                <option value="anak" {{ old('status_dalam_keluarga', $anggotaKeluarga->status_dalam_keluarga) == 'anak' ? 'selected' : '' }}>Anak</option>
                            </select>
                            @error('status_dalam_keluarga')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status_perkawinan" class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                            <select id="status_perkawinan" name="status_perkawinan" required
                                class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('status_perkawinan') border-red-500 @enderror">
                                <option value="menikah" {{ old('status_perkawinan', $anggotaKeluarga->status_perkawinan) == 'menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="belum_menikah" {{ old('status_perkawinan', $anggotaKeluarga->status_perkawinan) == 'belum_menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="cerai" {{ old('status_perkawinan', $anggotaKeluarga->status_perkawinan) == 'cerai' ? 'selected' : '' }}>Cerai</option>
                            </select>
                            @error('status_perkawinan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('jenis_kelamin') border-red-500 @enderror">
                                <option value="laki_laki" {{ old('jenis_kelamin', $anggotaKeluarga->jenis_kelamin) == 'laki_laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('jenis_kelamin', $anggotaKeluarga->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggotaKeluarga->tanggal_lahir ? $anggotaKeluarga->tanggal_lahir->format('Y-m-d') : '') }}" required
                                class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $anggotaKeluarga->pekerjaan) }}" required
                            class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('pekerjaan') border-red-500 @enderror">
                        @error('pekerjaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
