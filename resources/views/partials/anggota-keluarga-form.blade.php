{{-- Anggota Keluarga Dynamic Form Section --}}
<div class="mt-6 pt-5 border-t border-gray-200">
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-sm font-semibold text-gray-700">Anggota Keluarga</h4>
        <button type="button" id="btn-add-anggota"
            class="inline-flex items-center gap-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg px-3 py-1.5 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Anggota
        </button>
    </div>

    <div id="anggota-list" class="space-y-4"></div>

    <p class="mt-2 text-xs text-gray-400 italic" id="anggota-empty-hint">Belum ada anggota keluarga. Klik "Tambah Anggota" untuk menambahkan.</p>
</div>

{{-- Row template (hidden, cloned by JS) --}}
<template id="anggota-row-template">
    <div class="anggota-row border border-gray-200 rounded-xl p-4 bg-gray-50 relative">
        <button type="button" class="btn-remove-anggota absolute top-3 right-3 text-gray-400 hover:text-red-500 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            {{-- Nama --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="anggota_keluarga[__INDEX__][nama]"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 text-sm focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Nama anggota keluarga" required>
            </div>

            {{-- Status dalam keluarga --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status dalam Keluarga <span class="text-red-500">*</span></label>
                <select name="anggota_keluarga[__INDEX__][status_dalam_keluarga]"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 text-sm focus:border-purple-500 focus:ring-purple-500" required>
                    <option value="" disabled selected>-- Pilih --</option>
                    <option value="suami">Suami</option>
                    <option value="istri">Istri</option>
                    <option value="anak">Anak</option>
                </select>
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="anggota_keluarga[__INDEX__][jenis_kelamin]"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 text-sm focus:border-purple-500 focus:ring-purple-500" required>
                    <option value="" disabled selected>-- Pilih --</option>
                    <option value="laki_laki">Laki-laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
            </div>

            {{-- Status Perkawinan --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status Perkawinan <span class="text-red-500">*</span></label>
                <select name="anggota_keluarga[__INDEX__][status_perkawinan]"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 text-sm focus:border-purple-500 focus:ring-purple-500" required>
                    <option value="" disabled selected>-- Pilih --</option>
                    <option value="menikah">Menikah</option>
                    <option value="belum_menikah">Belum Menikah</option>
                    <option value="cerai">Cerai</option>
                </select>
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                <input type="date" name="anggota_keluarga[__INDEX__][tanggal_lahir]"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 text-sm focus:border-purple-500 focus:ring-purple-500">
            </div>

            {{-- Pekerjaan --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan</label>
                <input type="text" name="anggota_keluarga[__INDEX__][pekerjaan]"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 text-sm focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Pekerjaan (opsional)">
            </div>

            {{-- Status Khusus (meninggal / hamil) --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status Khusus</label>
                <select name="anggota_keluarga[__INDEX__][status]"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 text-sm focus:border-purple-500 focus:ring-purple-500">
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
    let index = 0;
    const list     = document.getElementById('anggota-list');
    const hint     = document.getElementById('anggota-empty-hint');
    const template = document.getElementById('anggota-row-template');

    function updateHint() {
        hint.style.display = list.children.length === 0 ? 'block' : 'none';
    }

    function addRow(data) {
        const clone = template.content.cloneNode(true);
        const row   = clone.querySelector('.anggota-row');
        const idx   = index++;

        // Replace placeholder index in all name attributes
        row.querySelectorAll('[name]').forEach(function (el) {
            el.name = el.name.replace(/__INDEX__/g, idx);
        });

        if (data) {
            const fields = ['nama', 'status_dalam_keluarga', 'jenis_kelamin', 'status_perkawinan', 'tanggal_lahir', 'pekerjaan', 'status'];
            fields.forEach(function (field) {
                const el = row.querySelector('[name="anggota_keluarga[' + idx + '][' + field + ']"]');
                if (el && data[field] !== undefined && data[field] !== null) {
                    el.value = data[field];
                }
            });
        }

        row.querySelector('.btn-remove-anggota').addEventListener('click', function () {
            row.remove();
            updateHint();
        });

        list.appendChild(row);
        updateHint();
    }

    document.getElementById('btn-add-anggota').addEventListener('click', function () {
        addRow(null);
    });

    // Seed rows
    @if(old('anggota_keluarga'))
        const oldData = @json(old('anggota_keluarga'));
        oldData.forEach(function (d) { addRow(d); });
    @elseif(isset($user) && $user->anggotaKeluarga && $user->anggotaKeluarga->isNotEmpty())
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
        const existingData = @json($existingAnggota);
        existingData.forEach(function (d) { addRow(d); });
    @endif

    updateHint();
})();
</script>
