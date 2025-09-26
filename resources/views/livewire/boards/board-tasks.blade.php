<div class="p-6 max-w-6xl mx-auto">

    <!-- Search -->
    <div class="relative mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
        </svg>
        <input type="text" placeholder="Search tasks..." 
               wire:model.debounce.500ms="search"
               class="pl-10 pr-4 py-2 w-full rounded-xl border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"/>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-8">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-4 w-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H3V4zM3 9h18M3 14h18M3 19h18"/>
            </svg>
            <select wire:model="status" 
                    class="pl-9 pr-4 py-2 rounded-xl bg-white border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-gray-700">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
            </select>
        </div>

        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-4 w-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .843-3 1.875S10.343 11.75 12 11.75s3-.843 3-1.875S13.657 8 12 8zm0 7c-1.657 0-3 .843-3 1.875S10.343 18.75 12 18.75s3-.843 3-1.875S13.657 15 12 15z"/>
            </svg>
            <select wire:model="priority" 
                    class="pl-9 pr-4 py-2 rounded-xl bg-white border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-gray-700">
                <option value="">All Priority</option>
                <option value="low">Low</option>
                <option value="normal">Normal</option>
                <option value="high">High</option>
            </select>
        </div>

        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-4 w-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a8 8 0 10-16 0v2h5"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <select wire:model="assignee" 
                    class="pl-9 pr-4 py-2 rounded-xl bg-white border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-gray-700">
                <option value="">All Assignees</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tasks as $task)
            <div class="p-5 bg-white rounded-2xl shadow hover:shadow-xl transition border border-gray-100">
                <h3 class="font-semibold text-lg text-gray-800 mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 11-4 0 2 2 0 014 0zM9 12a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ $task->title }}
                </h3>
                <p class="text-gray-600 text-sm mb-3">{{ $task->description }}</p>
                
                <div class="flex items-center gap-3 text-sm">
                    <span class="px-2 py-1 rounded-lg text-white text-xs font-medium 
                                 {{ $task->status === 'done' ? 'bg-green-500' : ($task->status === 'in_progress' ? 'bg-blue-500' : 'bg-gray-400') }}">
                        {{ ucfirst($task->status) }}
                    </span>
                    <span class="px-2 py-1 rounded-lg text-white text-xs font-medium 
                                 {{ $task->priority === 'high' ? 'bg-red-500' : ($task->priority === 'normal' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center py-16 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3 3v-6m9 5a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-lg font-medium">No tasks found</p>
                <p class="text-sm">Try adjusting your filters or create a new task.</p>
            </div>
        @endforelse
    </div>

</div>
