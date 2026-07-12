<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            {{ __('Kelola Kelompok') }}
        </h2>
    </x-slot>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-row items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 shrink-0">Daftar Kelompok</h3>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('admin.kelompoks.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            id="kelompok-search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama kelompok..."
                            class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent w-64"
                        />
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.kelompoks.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            Reset
                        </a>
                    @endif
                </form>
                <a href="{{ route('admin.kelompoks.exportPdf') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition" title="Export semua kelompok ke PDF">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.kelompoks.create') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Kelompok
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama Kelompok</th>
                        <th class="px-6 py-3">Jorong</th>
                        <th class="px-6 py-3">Jumlah Anggota</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($kelompoks as $kelompok)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($kelompoks->currentPage() - 1) * $kelompoks->perPage() }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            <a href="{{ route('admin.kelompoks.show', $kelompok) }}" class="hover:text-amber-600 hover:underline transition">
                                {{ $kelompok->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $kelompok->jorong_label }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $kelompok->users_count }} anggota
                            </span>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-2">
                            <a href="{{ route('admin.kelompoks.show', $kelompok) }}" class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 text-amber-700 hover:bg-amber-200 transition" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.kelompoks.exportPdfDetail', $kelompok) }}" class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" title="Export Detail Kelompok ke PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.kelompoks.edit', $kelompok) }}" class="inline-flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.kelompoks.destroy', $kelompok) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus kelompok ini? Semua anggota akan dilepas dari kelompok.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $kelompoks->links() }}
        </div>
    </div>
</x-app-layout>
