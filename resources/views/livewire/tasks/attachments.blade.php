<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <!-- Upload Form -->
    <form wire:submit.prevent="upload" 
          class="flex items-center gap-4 bg-gray-50 border border-dashed border-gray-300 rounded-2xl p-5 hover:border-indigo-400 hover:bg-gray-100 transition">
        
        <label class="flex items-center gap-3 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M4 12l4-4m0 0l4-4m-4 4v12" />
            </svg>
            <input type="file" wire:model="file" class="hidden">
            <span class="text-gray-600 font-medium">Choose a file</span>
        </label>

        <button type="submit" 
                class="ml-auto inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium px-5 py-2.5 rounded-xl shadow hover:opacity-90 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm4 4h8v8H8V8z"/>
            </svg>
            Upload
        </button>
    </form>

    <!-- Attachments List -->
    <div class="mt-6 space-y-4">
        @foreach($attachments as $attachment)
            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition">
                
                <div class="flex items-center gap-4">
                    @if(Str::startsWith($attachment->mime, 'image/'))
                        <img src="{{ asset('storage/'.$attachment->path) }}" 
                             alt="Attachment" class="w-14 h-14 object-cover rounded-lg border">
                    @else
                        <div class="flex items-center gap-2 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553 2.276A2 2 0 0120 14.118V17a2 2 0 01-2 2h-1v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1H6a2 2 0 01-2-2v-2.882a2 2 0 01.447-1.342L9 10V5a3 3 0 116 0v5z" />
                            </svg>
                            <a href="{{ asset('storage/'.$attachment->path) }}" target="_blank" 
                               class="font-medium hover:underline">
                                {{ $attachment->original_name }}
                            </a>
                        </div>
                    @endif

                    <span class="text-sm text-gray-500">
                        {{ number_format($attachment->size / 1024, 1) }} KB
                    </span>
                </div>

                <button wire:click="delete({{ $attachment->id }})"
                        class="inline-flex items-center gap-1 text-red-600 font-medium hover:text-red-700 hover:underline transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Delete
                </button>
            </div>
        @endforeach
    </div>
</div>
