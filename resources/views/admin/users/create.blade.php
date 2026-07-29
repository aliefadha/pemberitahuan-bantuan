<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Tambah User') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Form Tambah User</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Kembali</a>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p id="server-email-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p id="client-email-error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>

                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. HP (WhatsApp)</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('no_telepon') border-red-500 @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxx">
                        @error('no_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Contoh: 081234567890</p>
                    </div>

                    @if(auth()->user()->isKader())
                        <input type="hidden" id="role" name="role" value="peserta">
                        <input type="hidden" id="jorong" name="jorong" value="{{ auth()->user()->jorong }}">
                    @else
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('role') border-red-500 @enderror" id="role" name="role" required>
                                <option value="" disabled selected>-- Pilih Role --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="peserta" {{ old('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                <option value="kader" {{ old('role') == 'kader' ? 'selected' : '' }}>Kader</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="jorong-container">
                            <label for="jorong" class="block text-sm font-medium text-gray-700 mb-1">Jorong <span class="text-red-500">*</span></label>
                            <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('jorong') border-red-500 @enderror" id="jorong" name="jorong">
                                <option value="">-- Pilih Jorong --</option>
                                <option value="padang_rantang" {{ old('jorong') == 'padang_rantang' ? 'selected' : '' }}>Padang Rantang</option>
                                <option value="tanjung_pati" {{ old('jorong') == 'tanjung_pati' ? 'selected' : '' }}>Tanjung Pati</option>
                                <option value="koto_tuo" {{ old('jorong') == 'koto_tuo' ? 'selected' : '' }}>Koto Tuo</option>
                                <option value="pulutan" {{ old('jorong') == 'pulutan' ? 'selected' : '' }}>Pulutan</option>
                            </select>
                            @error('jorong')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="kelompok-container">
                            <label for="kelompok_id" class="block text-sm font-medium text-gray-700 mb-1">Kelompok <span class="text-red-500">*</span></label>
                            <select class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('kelompok_id') border-red-500 @enderror" id="kelompok_id" name="kelompok_id">
                                <option value="">-- Pilih Kelompok --</option>
                                @foreach($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->id }}" data-jorong="{{ $kelompok->jorong }}" {{ old('kelompok_id') == $kelompok->id ? 'selected' : '' }}>
                                        {{ $kelompok->name }} — {{ $kelompok->jorong_label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelompok_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500 @error('password') border-red-500 @enderror" id="password" name="password" required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" class="w-full rounded-lg border border-gray-300 shadow-sm py-1.5 px-2 focus:border-gray-500 focus:ring-gray-500" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div id="anggota-keluarga-container">
                            @include('partials.anggota-keluarga-form')
                        </div>
                    @endif

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var roleSelect = document.getElementById('role');
        var jorongContainer = document.getElementById('jorong-container');
        var jorongSelect = document.getElementById('jorong');
        var kelompokContainer = document.getElementById('kelompok-container');
        var kelompokSelect = document.getElementById('kelompok_id');
        var anggotaKeluargaContainer = document.getElementById('anggota-keluarga-container');

        function handleRoleChange() {
            var selectedRole = roleSelect ? roleSelect.value : 'peserta';

            // Show family members only if role is 'peserta' (anggota)
            if (selectedRole === 'peserta') {
                if (anggotaKeluargaContainer) {
                    anggotaKeluargaContainer.style.display = 'block';
                }
            } else {
                if (anggotaKeluargaContainer) {
                    anggotaKeluargaContainer.style.display = 'none';
                }
            }

            // Hide jorong input for 'admin', show and make required for others
            if (selectedRole === 'admin') {
                if (jorongContainer) {
                    jorongContainer.style.display = 'none';
                }
                if (jorongSelect) {
                    jorongSelect.removeAttribute('required');
                }
            } else {
                if (jorongContainer) {
                    jorongContainer.style.display = 'block';
                }
                if (jorongSelect && jorongSelect.tagName === 'SELECT') {
                    jorongSelect.setAttribute('required', 'required');
                }
            }

            if (kelompokContainer) {
                kelompokContainer.style.display = ['kader', 'peserta'].includes(selectedRole) ? 'block' : 'none';
            }
            if (kelompokSelect) {
                if (['kader', 'peserta'].includes(selectedRole)) {
                    kelompokSelect.setAttribute('required', 'required');
                    filterKelompok();
                } else {
                    kelompokSelect.removeAttribute('required');
                    kelompokSelect.value = '';
                }
            }
        }

        function filterKelompok() {
            if (!kelompokSelect || !jorongSelect) {
                return;
            }

            var selectedJorong = jorongSelect.value;
            Array.from(kelompokSelect.options).forEach(function (option, index) {
                if (index === 0) {
                    return;
                }

                var matchesJorong = option.dataset.jorong === selectedJorong;
                option.hidden = !matchesJorong;
                option.disabled = !matchesJorong;
                if (!matchesJorong && option.selected) {
                    kelompokSelect.value = '';
                }
            });
        }

        if (roleSelect && roleSelect.tagName === 'SELECT') {
            roleSelect.addEventListener('change', handleRoleChange);
        }
        if (jorongSelect && jorongSelect.tagName === 'SELECT') {
            jorongSelect.addEventListener('change', filterKelompok);
        }
        handleRoleChange(); // Run on initial load

        // Email Real-time Client-side Validation
        var emailInput = document.getElementById('email');
        var clientEmailError = document.getElementById('client-email-error');
        var serverEmailError = document.getElementById('server-email-error');
        var hasInteracted = false;

        function validateEmail() {
            var emailVal = emailInput.value.trim();
            var emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

            if (serverEmailError) {
                serverEmailError.style.display = 'none';
            }

            if (emailVal === '') {
                emailInput.classList.remove('border-gray-300', 'border-green-500');
                emailInput.classList.add('border-red-500');
                clientEmailError.textContent = 'Email wajib diisi.';
                clientEmailError.classList.remove('hidden');
                return false;
            } else if (!emailRegex.test(emailVal)) {
                emailInput.classList.remove('border-gray-300', 'border-green-500');
                emailInput.classList.add('border-red-500');
                clientEmailError.textContent = 'format harus @gmail.com (contoh: nama@gmail.com).';
                clientEmailError.classList.remove('hidden');
                return false;
            } else {
                emailInput.classList.remove('border-gray-300', 'border-red-500');
                emailInput.classList.add('border-green-500');
                clientEmailError.classList.add('hidden');
                return true;
            }
        }

        if (emailInput) {
            emailInput.addEventListener('blur', function () {
                hasInteracted = true;
                validateEmail();
            });

            emailInput.addEventListener('input', function () {
                if (hasInteracted) {
                    validateEmail();
                } else {
                    if (serverEmailError) {
                        serverEmailError.style.display = 'none';
                    }
                }
            });

            var form = emailInput.closest('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    hasInteracted = true;
                    if (!validateEmail()) {
                        e.preventDefault();
                        emailInput.focus();
                    }
                });
            }
        }
    });
    </script>
</x-app-layout>
