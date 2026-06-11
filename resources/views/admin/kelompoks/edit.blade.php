<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('Edit Kelompok') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mb-6 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Form Edit Kelompok</h3>
                <a href="{{ route('admin.kelompoks.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('admin.kelompoks.update', $kelompok) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelompok <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $kelompok->name) }}" required autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jorong" class="block text-sm font-medium text-gray-700 mb-1">Jorong <span class="text-red-500">*</span></label>
                        <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('jorong') border-red-500 @enderror" id="jorong" name="jorong" required>
                            <option value="">-- Pilih Jorong --</option>
                            <option value="padang_rantang" {{ old('jorong', $kelompok->jorong) == 'padang_rantang' ? 'selected' : '' }}>Padang Rantang</option>
                            <option value="tanjung_pati"   {{ old('jorong', $kelompok->jorong) == 'tanjung_pati'   ? 'selected' : '' }}>Tanjung Pati</option>
                            <option value="koto_tuo"       {{ old('jorong', $kelompok->jorong) == 'koto_tuo'       ? 'selected' : '' }}>Koto Tuo</option>
                            <option value="pulutan"        {{ old('jorong', $kelompok->jorong) == 'pulutan'        ? 'selected' : '' }}>Pulutan</option>
                        </select>
                        @error('jorong')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Anggota Kelompok Section --}}
                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anggota Kelompok</label>
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 max-h-72 overflow-y-auto space-y-2">
                            @forelse($allUsers as $user)
                                @php
                                    $isChecked = in_array((string) $user->id, old('users', $kelompok->users->pluck('id')->map(fn($id) => (string) $id)->toArray()));
                                @endphp
                                <label data-user-jorong="{{ $user->jorong ?? '' }}" class="user-row flex items-center gap-3 cursor-pointer hover:bg-white rounded-lg p-2 transition group">
                                    <input
                                        type="checkbox"
                                        name="users[]"
                                        value="{{ $user->id }}"
                                        {{ $isChecked ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-500"
                                    >
                                    <span class="flex-1 text-sm text-gray-800 font-medium">{{ $user->name }}</span>
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">Tidak ada user tersedia.</p>
                            @endforelse
                            <p id="no-users-notice" class="text-sm text-gray-500 text-center py-4" style="display: none;"></p>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500">Centang user yang akan menjadi anggota kelompok ini. User yang tidak dicentang akan dilepas dari kelompok.</p>
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.kelompoks.index') }}" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var jorongSel = document.getElementById('jorong');
        var userRows  = document.querySelectorAll('.user-row');
        var notice    = document.getElementById('no-users-notice');

        function filterUsers() {
            var selectedJorong = jorongSel.value;
            var visibleCount = 0;

            userRows.forEach(function (row) {
                var jorong = row.dataset.userJorong;
                var checkbox = row.querySelector('input[type="checkbox"]');

                if (jorong === selectedJorong) {
                    row.style.display = 'flex';
                    checkbox.disabled = false;
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    checkbox.checked = false;
                    checkbox.disabled = true;
                }
            });

            if (notice) {
                if (!selectedJorong) {
                    notice.textContent = 'Pilih jorong terlebih dahulu untuk melihat anggota yang tersedia.';
                    notice.style.display = 'block';
                } else if (visibleCount === 0) {
                    notice.textContent = 'Tidak ada user tersedia di jorong ini.';
                    notice.style.display = 'block';
                } else {
                    notice.style.display = 'none';
                }
            }
        }

        if (jorongSel) {
            jorongSel.addEventListener('change', filterUsers);
            // Run initially
            filterUsers();
        }
    })();
    </script>
</x-app-layout>
