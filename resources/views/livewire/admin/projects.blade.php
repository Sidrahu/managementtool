<div class="p-6 space-y-6">

    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-3xl font-extrabold text-gray-900">Projects</h1>

        <input type="text" wire:model.debounce.300ms="search" placeholder="Search projects..."
               class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-full md:w-1/3" />
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 p-5 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $project->name }}</h2>
                    <span class="text-sm text-gray-500">ID: {{ $project->id }}</span>
                </div>
                <p class="text-gray-600 mb-4 truncate">{{ $project->description ?? 'No description provided' }}</p>

                <div class="flex flex-wrap gap-2 mt-auto">
                    @foreach($project->users as $user)
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">
                            {{ $user->name }}
                        </span>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <a href="{{ route('projects.edit', $project->id) }}"
                       class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded transition">Edit</a>
                    <a href="{{ route('projects.show', $project->id) }}"
                       class="text-sm text-gray-700 border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded transition">View</a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-10">
                No projects found.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $projects->links() }}
    </div>

</div>
