<div class="max-w-6xl mx-auto py-10 px-6 space-y-8">

    <!-- Project Header -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 p-8 flex justify-between items-center relative overflow-hidden">
        
        <!-- Gradient Accent -->
        <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-blue-500 to-indigo-500 rounded-l-2xl"></div>

        <!-- Project Info -->
        <div class="ml-4">
            <div class="flex items-center gap-3 mb-2">
                <!-- Project Icon -->
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-7 w-7 text-blue-600" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7h18M3 12h18M3 17h18" />
                    </svg>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $project->name }}</h2>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $project->description }}</p>
        </div>

        <!-- Project Members & Invite -->
        <div class="flex items-center gap-6">
            <!-- Members -->
            <div class="flex -space-x-3">
                @foreach($project->members->take(5) as $member)
                    <img src="https://ui-avatars.com/api/?name={{ $member->name }}" 
                         class="w-10 h-10 rounded-full border-2 border-white shadow ring-2 ring-blue-100 transition hover:scale-105">
                @endforeach
                @if($project->members->count() > 5)
                    <div class="w-10 h-10 flex items-center justify-center bg-gray-200 text-gray-600 rounded-full text-sm font-semibold border-2 border-white shadow ring-2 ring-gray-300">
                        +{{ $project->members->count() - 5 }}
                    </div>
                @endif
            </div>

            <!-- Invite Button -->
            <button class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-5 py-2.5 rounded-xl shadow-lg transition-all hover:shadow-xl hover:scale-105 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4v16m8-8H4" />
                </svg>
                Invite
            </button>
        </div>
    </div>

    <!-- Invite Member Component -->
    <div>
        @livewire('projects.invite-member', ['project' => $project])
    </div>

    <!-- Boards Section -->
    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <!-- Board Icon -->
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-6 w-6 text-blue-500" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 6h18M3 12h18M3 18h18" />
                    </svg>
                </div>
                Project Boards
            </h3>
        </div>

        <!-- Boards Index -->
        <div class="space-y-4">
            @livewire('boards.index', ['project' => $project])
        </div>
    </div>

    <!-- Livewire Refresh Boards -->
    <script>
        Livewire.on('board-added', () => {
            Livewire.emit('refreshBoards'); 
        });
    </script>
</div>
