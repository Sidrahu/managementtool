<div class="max-w-7xl mx-auto py-12 px-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <h2 class="text-3xl font-extrabold flex items-center gap-3 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-500 bg-clip-text text-transparent drop-shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
            </svg>
            Boards for {{ $project->name }}
        </h2>
        <a href="{{ route('projects.boards.create', ['project' => $project->id]) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl font-semibold text-white shadow-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Board
        </a>
    </div>

    <!-- Flash message -->
    @if(session()->has('message'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm mb-8 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-10">
        <select wire:model="status" class="px-4 py-2.5 rounded-xl bg-white/80 backdrop-blur border border-gray-200 focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm transition">
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>

        <select wire:model="priority" class="px-4 py-2.5 rounded-xl bg-white/80 backdrop-blur border border-gray-200 focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm transition">
            <option value="">All Priority</option>
            <option value="low">Low</option>
            <option value="normal">Normal</option>
            <option value="high">High</option>
        </select>

        <select wire:model="assignee" class="px-4 py-2.5 rounded-xl bg-white/80 backdrop-blur border border-gray-200 focus:ring-2 focus:ring-blue-500 text-gray-700 shadow-sm transition">
            <option value="">All Assignees</option>
            @foreach($project->members as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Boards Grid with Polling -->
    <div wire:poll.15s>
        <ul id="board-list" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($boards as $board)
                <li data-id="{{ $board->id }}" 
                    class="bg-white border border-gray-100 rounded-2xl shadow-md p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    <div class="flex justify-between items-start">
                        <span class="font-bold text-lg text-gray-800 tracking-tight">{{ $board->name }}</span>

                        <div class="flex gap-2">
                            <a href="{{ route('projects.boards.kanban', ['board' => $board->id]) }}"
                               class="inline-flex items-center gap-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium shadow transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Kanban
                            </a>
                            <button wire:click="deleteBoard({{ $board->id }})"
                                    class="inline-flex items-center gap-1 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium shadow transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress</span>
                            <span class="font-medium text-gray-800">{{ $board->progress_percent }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-700 ease-out"
                                 style="width: {{ $board->progress_percent }}%">
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- SortableJS for drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('livewire:init', () => {
    let el = document.getElementById('board-list');
    new Sortable(el, {
        animation: 200,
        ghostClass: 'opacity-40',
        onEnd: function (evt) {
            let orderedIds = Array.from(el.children).map(li => li.dataset.id);
            Livewire.dispatch('boardReordered', { orderedIds });
        }
    });
});
</script>
