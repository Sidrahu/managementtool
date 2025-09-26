<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell Button -->
    <button @click="open = !open" class="relative p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition">
        <!-- Bell Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-6 w-6 text-gray-600" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" 
                d="M14.243 17.657A6 6 0 015.757 9.172m12.486 0a6 6 0 00-12.486 0c0 3.314-1.343 5.657-3 6h18c-1.657-.343-3-2.686-3-6z" />
        </svg>

        <!-- Notification Count Badge -->
        @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5">
                {{ Auth::user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-xl z-50 overflow-hidden">
        
        @forelse(Auth::user()->unreadNotifications as $notification)
            <div class="p-3 border-b hover:bg-gray-50 cursor-pointer transition"
                 wire:click="markAsRead('{{ $notification->id }}')">
                <p class="font-semibold text-gray-800 text-sm">{{ $notification->data['title'] }}</p>
                <p class="text-xs text-gray-600">{{ $notification->data['message'] }}</p>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 text-sm">
                No new notifications
            </div>
        @endforelse
    </div>
</div>
