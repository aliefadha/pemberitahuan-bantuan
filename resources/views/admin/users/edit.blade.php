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
                                                        <option value="1" {{ old('bio_data.' . $q['key'], $user->bio_data[$q['key']] ?? '') == '1' ? 'selected' : '' }}>Ya</option>
                                                        <option value="0" {{ old('bio_data.' . $q['key'], $user->bio_data[$q['key']] ?? '') == '0' ? 'selected' : '' }}>Tidak</option>
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
