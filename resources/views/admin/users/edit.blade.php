<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Form Edit User</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. HP (WhatsApp)</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('no_telepon') border-red-500 @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="08xxxxxxxxx">
                        @error('no_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Contoh: 081234567890</p>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('role') border-red-500 @enderror" id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="peserta" {{ old('role', $user->role) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelompok_id" class="block text-sm font-medium text-gray-700 mb-1">Kelompok</label>
                        <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('kelompok_id') border-red-500 @enderror" id="kelompok_id" name="kelompok_id">
                            <option value="">-- Tidak ada --</option>
                            @foreach($kelompoks as $k)
                                <option value="{{ $k->id }}" {{ old('kelompok_id', $user->kelompok_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }}{{ $k->jorong ? ' — ' . $k->jorong_label : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelompok_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Kosongkan password jika tidak ingin mengubahnya.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('password') border-red-500 @enderror" id="password" name="password">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    @if($user->role === 'peserta')
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Data Bio Peserta</h4>
                        <div class="space-y-6">
                            @foreach($sections as $section)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-4">{{ $section['title'] }}</h5>
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
                                                        value="{{ old('bio_data.' . $q['key'], $user->bio_data[$q['key']] ?? '') }}"
                                                        @isset($q['min']) min="{{ $q['min'] }}" @endisset
                                                        @isset($q['max']) max="{{ $q['max'] }}" @endisset
                                                        class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500"
                                                        placeholder="0">
                                                @elseif($q['type'] === 'boolean')
                                                    <select id="bio_data_{{ $q['key'] }}"
                                                        name="bio_data[{{ $q['key'] }}]"
                                                        class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500">
                                                        <option value="">Pilih</option>
                                                        <option value="1" {{ (string)old('bio_data.' . $q['key'], $user->bio_data[$q['key']] ?? '') === '1' ? 'selected' : '' }}>Ya</option>
                                                        <option value="0" {{ (string)old('bio_data.' . $q['key'], $user->bio_data[$q['key']] ?? '') === '0' ? 'selected' : '' }}>Tidak</option>
                                                    </select>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Anggota Keluarga Section --}}
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-base font-semibold text-gray-800">Anggota Keluarga</h4>
                            <button type="button" id="btn-add-anggota-edit"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg px-3 py-1.5 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Anggota
                            </button>
                        </div>

                        <div id="anggota-list-edit" class="space-y-4"></div>
                        <p class="mt-2 text-xs text-gray-400 italic" id="anggota-empty-hint-edit" style="display:none">Belum ada anggota keluarga.</p>
                    </div>

                    {{-- Row Template --}}
                    <template id="anggota-row-tpl">
                        <div class="anggota-row border border-gray-200 rounded-xl p-4 bg-gray-50 relative">
                            <button type="button" class="btn-remove-anggota absolute top-3 right-3 text-gray-400 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="anggota_keluarga[__I__][nama]"
                                        class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 text-sm" placeholder="Nama anggota" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Status dalam Keluarga <span class="text-red-500">*</span></label>
                                    <select name="anggota_keluarga[__I__][status_dalam_keluarga]" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 text-sm" required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        <option value="suami">Suami</option>
                                        <option value="istri">Istri</option>
                                        <option value="anak">Anak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <select name="anggota_keluarga[__I__][jenis_kelamin]" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 text-sm" required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        <option value="laki_laki">Laki-laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Status Perkawinan <span class="text-red-500">*</span></label>
                                    <select name="anggota_keluarga[__I__][status_perkawinan]" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 text-sm" required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        <option value="menikah">Menikah</option>
                                        <option value="belum_menikah">Belum Menikah</option>
                                        <option value="cerai">Cerai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                    <input type="date" name="anggota_keluarga[__I__][tanggal_lahir]"
                                        class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan</label>
                                    <input type="text" name="anggota_keluarga[__I__][pekerjaan]"
                                        class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 text-sm" placeholder="Opsional">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Status Khusus</label>
                                    <select name="anggota_keluarga[__I__][status]" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 text-sm">
                                        <option value="">-- Tidak ada --</option>
                                        <option value="meninggal">Meninggal</option>
                                        <option value="hamil">Hamil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>

                    <script>
                    (function () {
                        var idx  = 0;
                        var list = document.getElementById('anggota-list-edit');
                        var hint = document.getElementById('anggota-empty-hint-edit');
                        var tpl  = document.getElementById('anggota-row-tpl');

                        function updateHint() {
                            hint.style.display = list.children.length === 0 ? 'block' : 'none';
                        }

                        function addRow(data) {
                            var clone = tpl.content.cloneNode(true);
                            var row   = clone.querySelector('.anggota-row');
                            var i     = idx++;

                            row.querySelectorAll('[name]').forEach(function (el) {
                                el.name = el.name.replace(/__I__/g, i);
                            });

                            if (data) {
                                var fields = ['nama','status_dalam_keluarga','jenis_kelamin','status_perkawinan','tanggal_lahir','pekerjaan','status'];
                                fields.forEach(function (f) {
                                    var el = row.querySelector('[name="anggota_keluarga[' + i + '][' + f + ']"]');
                                    if (el && data[f] != null) el.value = data[f];
                                });
                            }

                            row.querySelector('.btn-remove-anggota').addEventListener('click', function () {
                                row.remove();
                                updateHint();
                            });

                            list.appendChild(row);
                            updateHint();
                        }

                        document.getElementById('btn-add-anggota-edit').addEventListener('click', function () {
                            addRow(null);
                        });

                        // Seed: prefer old() on validation failure, else existing DB records
                        @if(old('anggota_keluarga'))
                            @json(old('anggota_keluarga')).forEach(function (d) { addRow(d); });
                        @else
                            @php
                                $existingAnggota = $user->anggotaKeluarga->map(fn($a) => [
                                    'nama'                  => $a->nama,
                                    'status_dalam_keluarga' => $a->status_dalam_keluarga,
                                    'jenis_kelamin'         => $a->jenis_kelamin,
                                    'status_perkawinan'     => $a->status_perkawinan,
                                    'tanggal_lahir'         => $a->tanggal_lahir?->format('Y-m-d'),
                                    'pekerjaan'             => $a->pekerjaan,
                                    'status'                => $a->status,
                                ])->values()->all();
                            @endphp
                            var existing = @json($existingAnggota);
                            existing.forEach(function (d) { addRow(d); });
                        @endif

                        updateHint();
                    })();
                    </script>

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
