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
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. HP (WhatsApp)</label>
                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" autocomplete="tel"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('no_telepon') border-red-500 @enderror"
                    placeholder="Contoh: 081234567890">
                @error('no_telepon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Contoh: 081234567890</p>
            </div>

            {{-- Jorong --}}
            <div>
                <label for="jorong" class="block text-sm font-medium text-gray-700 mb-1">Jorong</label>
                <select id="jorong" name="jorong"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('jorong') border-red-500 @enderror">
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

            {{-- Kelompok (filtered by jorong via JS) --}}
            <div>
                <label for="kelompok_id" class="block text-sm font-medium text-gray-700 mb-1">Kelompok <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                <select id="kelompok_id" name="kelompok_id"
                    class="w-full rounded-lg border-gray-300 shadow-sm py-1.5 px-2 focus:border-purple-500 focus:ring-purple-500 @error('kelompok_id') border-red-500 @enderror">
                    <option value="">-- Pilih Kelompok --</option>
                    @foreach($kelompoks as $k)
                        <option
                            value="{{ $k->id }}"
                            data-jorong="{{ $k->jorong ?? '' }}"
                            {{ old('kelompok_id') == $k->id ? 'selected' : '' }}
                        >{{ $k->name }}</option>
                    @endforeach
                </select>
                @error('kelompok_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400" id="kelompok-hint" style="display:none">Pilih jorong terlebih dahulu untuk melihat kelompok yang tersedia.</p>
            </div>

            <script>
            (function () {
                var jorongSel   = document.getElementById('jorong');
                var kelompokSel = document.getElementById('kelompok_id');
                var hint        = document.getElementById('kelompok-hint');
                var allOptions  = Array.from(kelompokSel.options).slice(1); // skip placeholder

                function filterKelompok() {
                    var jorong = jorongSel.value;

                    allOptions.forEach(function (opt) {
                        var match = !jorong || opt.dataset.jorong === jorong;
                        opt.hidden   = !match;
                        opt.disabled = !match;
                    });

                    // Reset selection if current value is now hidden
                    var current = kelompokSel.options[kelompokSel.selectedIndex];
                    if (current && current.hidden) {
                        kelompokSel.value = '';
                    }

                    hint.style.display = jorong ? 'none' : 'block';
                }

                jorongSel.addEventListener('change', filterKelompok);

                // Run on page load to restore filtered state (e.g. after validation failure)
                filterKelompok();
            })();
            </script>

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

        @include('partials.anggota-keluarga-form')

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
</x-guest-layout>
