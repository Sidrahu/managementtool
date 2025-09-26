<div class="space-y-6">

    <!-- Add Comment Form -->
    <form wire:submit.prevent="addComment" class="bg-white shadow-md rounded-xl p-5 border border-gray-200 space-y-4">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8-1.27 0-2.47-.22-3.55-.62L3 20l1.35-2.7A7.963 7.963 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Add Comment
        </h3>

        <textarea wire:model.defer="newComment" rows="3"
                  class="w-full border rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                  placeholder="Write your thoughts..."></textarea>
        @error('newComment')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror

        <!-- File Upload -->
        <div>
            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-gray-600 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M15 7v10a4 4 0 01-8 0V7"/>
                </svg>
                Attach files
                <input type="file" wire:model="attachments" multiple class="hidden">
            </label>
            @error('attachments.*')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit"
                    class="px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg shadow hover:opacity-90 transition text-sm font-medium">
                Post Comment
            </button>
        </div>
    </form>

    <!-- Comments List -->
    <div class="space-y-3">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M7 8h10M7 12h6m-6 4h10M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H9l-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Comments
        </h3>
        @forelse($comments as $comment)
            <div class="bg-gray-50 rounded-lg p-4 border flex gap-3 hover:shadow transition">
                <!-- User Avatar Placeholder -->
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $comment->user->name }}
                        <span class="text-gray-500 text-xs ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                    </p>
                    <p class="text-gray-700 text-sm mt-1">{{ $comment->content }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No comments yet.</p>
        @endforelse
    </div>

    <!-- Attachments List -->
    <div class="space-y-2 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M15 7v10a4 4 0 01-8 0V7"/>
            </svg>
            Attachments
        </h3>
        @forelse($attachments as $attachment)
            <div class="bg-white border rounded-lg p-3 flex justify-between items-center hover:shadow transition">
                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                   class="text-blue-600 hover:underline font-medium text-sm">
                    {{ basename($attachment->file_path) }}
                </a>
                <span class="text-gray-500 text-xs">Uploaded by {{ $attachment->user->name }}</span>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No attachments uploaded yet.</p>
        @endforelse
    </div>
</div>
