<div>
    @if($open)
        <div class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6 animate-fade-in">
                
                <!-- Header -->
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        New Board
                    </h3>
                    <button type="button" wire:click="$set('open', false)" 
                            class="text-gray-400 hover:text-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="save" class="space-y-5">
                    <!-- Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Board Name</label>
                        <input type="text" wire:model="name" placeholder="Enter board name"
                               class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 shadow-sm">
                        @error('name') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('open', false)"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </button>

                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2 rounded-xl font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Trigger Button -->
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold shadow-md hover:scale-105 hover:from-blue-700 hover:to-indigo-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create Board
    </button>
</div>
