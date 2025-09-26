<div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <!-- Header -->
    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        @if($column->exists)
            <!-- Edit Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z" />
            </svg>
            Edit Column
        @else
            <!-- Plus Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Column
        @endif
    </h2>

    <!-- Form -->
    <form wire:submit.prevent="save" class="space-y-5">
        <!-- Input -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Column Name</label>
            <input type="text" 
                   wire:model="name" 
                   placeholder="Enter column name..."
                   class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 text-gray-800 placeholder-gray-400 transition">
            @error('name') 
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <button type="button" 
                    wire:click="$dispatch('closeModal')" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                <!-- Cancel Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancel
            </button>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow hover:opacity-90 transition">
                <!-- Save Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save
            </button>
        </div>
    </form>
</div>
