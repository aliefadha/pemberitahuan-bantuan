<x-guest-layout title="Daftar Akun Baru">
    <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
        @csrf

        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                    placeholder="Contoh: nama@email.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" autocomplete="street-address"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('alamat') border-red-500 @enderror"
                    placeholder="Masukkan alamat lengkap">
                @error('alamat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. HP (WhatsApp)</label>
                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" autocomplete="tel"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('no_telepon') border-red-500 @enderror"
                    placeholder="Contoh: 081234567890">
                @error('no_telepon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Contoh: 081234567890</p>
            </div>

            @if(auth()->check() && auth()->user()->isAdmin())
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select id="role" name="role"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('role') border-red-500 @enderror">
                    <option value="peserta" {{ old('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 pl-2 pr-10 focus:border-purple-500 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" onclick="const input = document.getElementById('password'); const type = input.getAttribute('type') === 'password' ? 'text' : 'password'; input.setAttribute('type', type); this.innerHTML = type === 'password' ? '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' /></svg>' : '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\' /></svg>';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                            class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 pl-2 pr-10 focus:border-purple-500 focus:ring-purple-500 @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Masukkan ulang password">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" onclick="const input = document.getElementById('password_confirmation'); const type = input.getAttribute('type') === 'password' ? 'text' : 'password'; input.setAttribute('type', type); this.innerHTML = type === 'password' ? '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' /></svg>' : '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\' /></svg>';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Anggota Keluarga</h3>
                <button type="button" id="addAnggotaKeluarga"
                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Anggota Keluarga
                </button>
            </div>
            <p class="text-xs text-gray-500 mb-4">Klik tombol di atas untuk menambahkan anggota keluarga (maksimal 20)</p>

            <div id="anggotaKeluargaContainer" class="space-y-3">
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full px-4 py-1.5 px-2 font-medium rounded-lg bg-black text-white">
                Daftar Akun
            </button>
        </div>
    </form>

    <div class="mt-4 pt-4 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-600">Sudah punya akun?
            <a href="{{ route('login') }}" class=" font-medium">Masuk</a>
        </p>
    </div>

    <template id="anggotaKeluargaTemplate">
        <div class="anggota-keluarga-card bg-gray-50 rounded-lg p-4 border border-gray-200" data-index="__INDEX__">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-700 anggota-keluarga-number">Anggota __NUMBER__</span>
                <button type="button" class="remove-anggota-keluarga inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded hover:bg-red-200 transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Hapus
                </button>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 text-sm"
                        placeholder="Nama" name="anggota_keluarga[__INDEX__][nama]">
                </div>
                <div>
                    <select class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 text-sm"
                        name="anggota_keluarga[__INDEX__][status_dalam_keluarga]">
                        <option value="">Status dalam Keluarga</option>
                        <option value="suami">Suami</option>
                        <option value="istri">Istri</option>
                        <option value="anak">Anak</option>
                    </select>
                </div>
                <div>
                    <select class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 text-sm"
                        name="anggota_keluarga[__INDEX__][status_perkawinan]">
                        <option value="">Status Perkawinan</option>
                        <option value="menikah">Menikah</option>
                        <option value="belum_menikah">Belum Menikah</option>
                        <option value="cerai">Cerai</option>
                    </select>
                </div>
                <div>
                    <select class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 text-sm"
                        name="anggota_keluarga[__INDEX__][jenis_kelamin]">
                        <option value="">Jenis Kelamin</option>
                        <option value="laki_laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <input type="date" class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 text-sm"
                        name="anggota_keluarga[__INDEX__][tanggal_lahir]">
                </div>
                <div class="sm:col-span-2">
                    <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 text-sm"
                        placeholder="Pekerjaan" name="anggota_keluarga[__INDEX__][pekerjaan]">
                </div>
            </div>
        </div>
    </template>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('anggotaKeluargaContainer');
    const addBtn = document.getElementById('addAnggotaKeluarga');
    const template = document.getElementById('anggotaKeluargaTemplate');
    const maxMembers = 20;
    let memberCount = 0;

    function updateIndices() {
        const cards = container.querySelectorAll('.anggota-keluarga-card');
        cards.forEach((card, i) => {
            card.dataset.index = i;
            card.querySelector('.anggota-keluarga-number').textContent = 'Anggota ' + (i + 1);
            card.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, '[' + i + ']'));
                }
            });
        });
    }

    addBtn.addEventListener('click', function() {
        if (memberCount >= maxMembers) {
            alert('Maksimal ' + maxMembers + ' anggota keluarga');
            return;
        }
        const html = template.innerHTML.replace(/__INDEX__/g, memberCount).replace(/__NUMBER__/g, memberCount + 1);
        container.insertAdjacentHTML('beforeend', html);
        memberCount++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-anggota-keluarga')) {
            e.target.closest('.anggota-keluarga-card').remove();
            memberCount--;
            updateIndices();
        }
    });

    @if(old('anggota_keluarga'))
    const oldData = @json(old('anggota_keluarga'));
    Object.values(oldData).forEach(function(member) {
        if (member && member.nama) {
            const html = template.innerHTML.replace(/__INDEX__/g, memberCount).replace(/__NUMBER__/g, memberCount + 1);
            const div = document.createElement('div');
            div.innerHTML = html;
            const card = div.firstElementChild;
            container.appendChild(card);
            card.querySelectorAll('input, select').forEach(input => {
                const parts = input.name.match(/\[(\w+)\]\[(\w+)\]/);
                if (parts && parts[1] && parts[2]) {
                    input.value = member[parts[2]] || '';
                }
            });
            memberCount++;
        }
    });
    @endif
});
</script>
@endpush
</x-guest-layout>
