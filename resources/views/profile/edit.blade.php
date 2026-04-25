<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Profile</h3>
                </div>
                <div class="p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Anggota Keluarga</h3>
                    <a href="{{ route('anggota-keluarga.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Anggota Keluarga
                    </a>
                </div>
                <div class="p-8">
                    @if(auth()->user()->anggotaKeluarga->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3">Nama</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">L/P</th>
                                        <th class="px-4 py-3">Tgl Lahir</th>
                                        <th class="px-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->anggotaKeluarga as $anggota)
                                    <tr class="border-b last:border-b-0 hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $anggota->nama }}</td>
                                        <td class="px-4 py-3 capitalize">{{ $anggota->status_dalam_keluarga }}</td>
                                        <td class="px-4 py-3">{{ $anggota->jenis_kelamin == 'laki_laki' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td class="px-4 py-3">{{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('anggota-keluarga.edit', $anggota->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                            <form method="POST" action="{{ route('anggota-keluarga.destroy', $anggota->id) }}" class="inline-block">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-sm text-gray-500 italic">Belum ada data anggota keluarga.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Update Password</h3>
                </div>
                <div class="p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-4 border-b border-gray-200 border-red-100 bg-red-50">
                    <h3 class="text-lg font-semibold text-red-600">Hapus Akun</h3>
                </div>
                <div class="p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
