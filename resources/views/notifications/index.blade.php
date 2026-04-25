<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            {{ __('Notifikasi') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-row items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Semua Notifikasi</h3>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Mark all as read</button>
                </form>
            @endif
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ isset($notification->data['submission_status']) ? ($notification->data['submission_status'] == 'accepted' ? 'bg-green-100' : 'bg-red-100') : 'bg-gray-100' }}">
                                @if(isset($notification->data['submission_status']))
                                    @if($notification->data['submission_status'] == 'accepted')
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                @if(isset($notification->data['catatan']) && $notification->data['catatan'])
                                    <p class="mt-1 text-sm text-gray-600"><strong>Catatan:</strong> {{ $notification->data['catatan'] }}</p>
                                @endif
                            </div>
                        </div>
                        <small class="text-sm text-gray-500 whitespace-nowrap">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    @if(is_null($notification->read_at))
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Tandai sudah dibaca</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">Tidak ada notifikasi.</div>
            @endforelse
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>