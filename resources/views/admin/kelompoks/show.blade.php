<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            {{ __('Detail Kelompok') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mb-6 space-y-6">
        <!-- Kelompok Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Informasi Kelompok</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Detail dan data anggota kelompok bantuan</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.kelompoks.exportPdfDetail', $kelompok) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition" title="Export PDF">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('admin.kelompoks.edit', $kelompok) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 text-white text-xs font-semibold rounded-lg hover:bg-gray-800 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.kelompoks.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                        Kembali
                    </a>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6 bg-white">
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Nama Kelompok</span>
                    <span class="text-lg font-bold text-gray-900">{{ $kelompok->name }}</span>
                </div>
                
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Jorong</span>
                    <span class="text-lg font-bold text-gray-900">{{ $kelompok->jorong_label }}</span>
                </div>

                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Jumlah Anggota</span>
                    <span class="text-lg font-bold text-amber-600">
                        {{ $kelompok->users->count() }} <span class="text-sm font-medium text-gray-500">Orang</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Members Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Daftar Anggota Kelompok</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3.5" style="width: 60px;">No</th>
                            <th class="px-6 py-3.5">Nama Lengkap</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5">Nomor WhatsApp</th>
                            <th class="px-6 py-3.5 text-center">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kelompok->users as $index => $peserta)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $peserta->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $peserta->email }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($peserta->no_telepon)
                                    <a href="https://wa.me/{{ $peserta->whatsapp_number }}" target="_blank" class="inline-flex items-center gap-1.5 text-green-600 hover:text-green-800 hover:underline font-medium">
                                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.197 1.449 4.805 1.45h.007c5.485 0 9.947-4.437 9.95-9.897.002-2.646-1.018-5.132-2.871-6.99C16.68 1.86 14.184.835 11.53.835 6.046.835 1.583 5.272 1.58 10.732c-.001 1.73.453 3.418 1.314 4.908l-.961 3.513 3.61-.947.114.068c1.479.879 3.151 1.38 4.904 1.38zm10.237-7.054c-.269-.134-1.593-.787-1.839-.877-.246-.09-.425-.134-.604.134-.179.269-.694.877-.851 1.056-.157.179-.313.202-.582.068-.269-.134-1.137-.419-2.167-1.338-.801-.715-1.342-1.597-1.499-1.866-.157-.269-.017-.414.118-.548.121-.12.269-.314.403-.471.134-.157.179-.269.269-.449.09-.179.045-.336-.022-.471-.067-.134-.604-1.457-.828-1.995-.218-.524-.46-.453-.604-.461-.139-.007-.298-.008-.458-.008-.16 0-.42.06-.639.298-.22.239-.839.82-.839 2.001 0 1.181.86 2.321.98 2.478.12.157 1.691 2.582 4.097 3.619.572.247 1.018.394 1.366.504.575.183 1.098.157 1.512.095.462-.069 1.593-.651 1.817-1.28.223-.63.223-1.169.157-1.28-.067-.11-.246-.179-.515-.314z"/>
                                        </svg>
                                        {{ $peserta->no_telepon }}
                                    </a>
                                @else
                                    <span class="text-gray-400 font-normal italic">Tidak ada nomor</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $peserta->role === 'admin' || $peserta->role === 'kader' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($peserta->role) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 font-medium italic">
                                Belum ada anggota di kelompok ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
