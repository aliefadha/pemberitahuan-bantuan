<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pemberitahuan Dosen') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false, collapsed: false }" class="min-h-screen bg-gray-100">
        <x-sidebar />

        <div class="flex flex-col min-h-screen transition-all duration-300 ease-in-out" :class="collapsed ? 'lg:ml-16' : 'lg:ml-64'">
            <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button @click="collapsed = !collapsed" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center gap-4">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @php
                                        $unreadCount = Auth::user()->unreadNotifications()->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                    @endif
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                    <div class="p-3 border-b border-gray-200">
                                        <h3 class="font-semibold text-gray-700">Notifications</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        @php
                                            $notifications = Auth::user()->notifications()->limit(5)->get();
                                        @endphp
                                        @forelse($notifications as $notification)
                                            <div class="p-3 border-b border-gray-100 hover:bg-gray-50">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-{{ isset($notification->data['type']) && $notification->data['type'] == 'accepted' ? 'green' : (isset($notification->data['type']) && $notification->data['type'] == 'rejected' ? 'red' : 'blue') }}-100">
                                                        <svg class="w-4 h-4 text-{{ isset($notification->data['type']) && $notification->data['type'] == 'accepted' ? 'green' : (isset($notification->data['type']) && $notification->data['type'] == 'rejected' ? 'red' : 'blue') }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $notification->data['message'] ?? $notification->data['dokumen_id'] ?? 'New notification' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-4 text-center text-gray-500 text-sm">No notifications</div>
                                        @endforelse
                                    </div>
                                    @if($notifications->count() > 0)
                                        <a href="{{ route('notifications.index') }}" class="block p-3 text-center text-sm text-blue-600 hover:bg-gray-50 border-t border-gray-200">
                                            Show All Alerts
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 p-2 text-gray-400 hover:text-gray-500">
                                    <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

            <footer class="bg-white border-t border-gray-200 py-6">
                <div class="px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">Copyright &copy; Pemberitahuan Bantuan {{ date('Y') }}</p>
                </div>
            </footer>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
