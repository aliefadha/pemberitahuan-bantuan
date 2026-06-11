@props(['collapsed' => false])

<aside x-data="{ open: false }"
    class="fixed left-0 top-0 z-50 h-screen bg-gray-900 text-white transition-all duration-300 ease-in-out"
    :class="collapsed ? 'w-16' : 'w-64'">

    <div class="flex flex-col h-full">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center h-16 bg-gray-800 border-b border-gray-700">
            <span class="font-bold text-lg" x-show="!collapsed">{{ __('Pemberitahuan Bantuan') }}</span>
            <span class="font-bold text-lg" x-show="collapsed">{{ __('PB') }}</span>
        </a>

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span x-show="!collapsed">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                    <li class="pt-4">
                        <span x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Admin') }}</span>
                    </li>

                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span x-show="!collapsed">{{ __('Kelola User') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.kelompoks.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.kelompoks.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span x-show="!collapsed">{{ __('Kelola Kelompok') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.kegiatans.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.kegiatans.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span x-show="!collapsed">{{ __('Kelola Kegiatan') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.whatsapp.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.whatsapp.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span x-show="!collapsed">{{ __('WhatsApp') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('notifications.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('notifications.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="!collapsed">{{ __('Notifikasi') }}</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isPeserta())
                    <li class="pt-4">
                        <span x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Menu') }}</span>
                    </li>

                    <li>
                        <a href="{{ route('kegiatan.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('kegiatan.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span x-show="!collapsed">{{ __('Daftar Kegiatan') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('bio.show') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('bio.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span x-show="!collapsed">{{ __('Profil') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
