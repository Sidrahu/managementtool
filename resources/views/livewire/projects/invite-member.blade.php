<div class="max-w-lg mx-auto bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657-1.343-3-3-3S6 9.343 6 11s1.343 3 3 3 3-1.343 3-3zM18 11c0-1.657-1.343-3-3-3s-3 1.343-3 3 1.343 3 3 3 3-1.343 3-3zM12 20c0-2.761-2.239-5-5-5s-5 2.239-5 5"/>
        </svg>
        Invite Member
    </h2>

    <form wire:submit.prevent="invite" class="flex gap-3">
        <input type="email" 
               wire:model="email" 
               placeholder="Enter member email"
               class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none text-gray-700 placeholder-gray-400">
        
        <button type="submit" 
                class="bg-green-600 hover:bg-green-700 transition-colors text-white px-5 py-2 rounded-xl font-medium shadow">
            Send Invite
        </button>
    </form>

    @error('email') 
        <p class="text-red-500 text-sm mt-3 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $message }}
        </p> 
    @enderror

    @if (session('message'))
        <p class="text-green-600 text-sm mt-3 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('message') }}
        </p>
    @endif
</div>
