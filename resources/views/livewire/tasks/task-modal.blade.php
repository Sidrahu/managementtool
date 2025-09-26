<div>
    @if($showModal)
        <!-- Overlay -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <!-- Modal -->
            <div class="bg-white/95 backdrop-blur-md w-11/12 max-w-4xl rounded-2xl shadow-2xl p-8 overflow-y-auto max-h-[90vh] border border-gray-200">

                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-4 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <!-- Task Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                        </svg>
                        Task Details
                    </h2>
                    <button wire:click="$set('showModal', false)" 
                            class="p-2 rounded-full hover:bg-red-100 text-gray-600 hover:text-red-600 transition">
                        <!-- Close -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Task Info -->
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="text-sm font-semibold text-gray-500">Title</label>
                        <p class="text-xl font-medium text-gray-900">{{ $task->title }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="text-sm font-semibold text-gray-500">Description</label>
                        <p class="text-gray-700 leading-relaxed">{{ $task->description }}</p>
                    </div>

                    <!-- Status / Priority / Due Date -->
                    <div class="grid grid-cols-3 gap-6">
                        <!-- Status -->
                        <div>
                            <label class="flex items-center gap-1 text-sm font-semibold text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 6v6h4"/>
                                </svg>
                                Status
                            </label>
                            <span class="mt-1 inline-block px-3 py-1 text-xs font-medium rounded-full 
                                @if($task->status === 'done') bg-green-100 text-green-700 
                                @elseif($task->status === 'in-progress') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="flex items-center gap-1 text-sm font-semibold text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 9v2m0 4h.01M12 3a9 9 0 100 18a9 9 0 000-18z"/>
                                </svg>
                                Priority
                            </label>
                            <span class="mt-1 inline-block px-3 py-1 text-xs font-medium rounded-full 
                                @if($task->priority === 'high') bg-red-100 text-red-700
                                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="flex items-center gap-1 text-sm font-semibold text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                                </svg>
                                Due Date
                            </label>
                            <span class="block text-sm mt-1 {{ now()->gt($task->due_date) ? 'text-red-600 font-semibold' : 'text-gray-800' }}">
                                {{ \Carbon\Carbon::parse($task->due_date)->format('d M, Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div>
                        <label class="flex items-center gap-1 text-sm font-semibold text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 12a9 9 0 1118 0A9 9 0 013 12z"/>
                            </svg>
                            Progress
                        </label>
                        <div class="w-full bg-gray-200 rounded-full h-3 mt-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500"
                                style="width: {{ $task->progress }}%">
                            </div>
                        </div>
                        <small class="text-gray-500">{{ $task->progress }}% completed</small>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M15 7v10a4 4 0 01-8 0V7"/>
                        </svg>
                        Attachments
                    </h3>

                    @if($task->attachments->count() > 0)
                        <ul class="space-y-3">
                            @foreach($task->attachments as $attachment)
                                <li class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border hover:bg-gray-100 transition">
                                    <div class="flex items-center space-x-3">
                                        @if(Str::startsWith($attachment->mime, 'image/'))
                                            <img src="{{ Storage::url($attachment->path) }}" alt="{{ $attachment->original_name }}" class="w-12 h-12 rounded-lg object-cover shadow">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828V7h-2.828z"/>
                                                <path d="M16 5h2a2 2 0 012 2v2"/>
                                            </svg>
                                        @endif
                                        <div>
                                            <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="text-blue-600 hover:underline font-medium">
                                                {{ $attachment->original_name }}
                                            </a>
                                            <p class="text-xs text-gray-500">({{ number_format($attachment->size / 1024, 2) }} KB)</p>
                                        </div>
                                    </div>
                                    <button wire:click="deleteAttachment({{ $attachment->id }})" 
                                            class="p-2 rounded-full hover:bg-red-100 text-red-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 mt-2 text-sm">No attachments uploaded.</p>
                    @endif
                </div>

                <!-- Comments -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 border-b pb-2 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8-1.27 0-2.47-.22-3.55-.62L3 20l1.35-2.7A7.963 7.963 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Comments
                    </h3>

                    <!-- Add Comment -->
                    <form wire:submit.prevent="addComment" class="flex gap-3">
                        <input type="text" wire:model="newComment" class="flex-1 border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Write a comment...">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-5 py-2 rounded-lg text-sm font-medium shadow hover:opacity-90 transition">
                            Post
                        </button>
                    </form>

                    <!-- Comments List -->
                    <div class="mt-4 space-y-3 max-h-48 overflow-auto pr-2">
                        @forelse($task->comments as $comment)
                            <div class="p-3 rounded-lg bg-gray-50 border hover:shadow-sm transition">
                                <p class="text-sm text-gray-800">{{ $comment->body }}</p>
                                <small class="text-xs text-gray-500">By {{ $comment->user->name }} • {{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No comments yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
