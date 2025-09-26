<div class="max-w-7xl mx-auto py-12 px-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-9 w-9 text-blue-600" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 7h4l2-2h8l2 2h4v12H3V7z" />
                </svg>
                <span>My Projects</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Manage your projects and collaborate with team members.</p>
        </div>

        <a href="{{ route('projects.create') }}" 
           class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-xl shadow-lg transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 mr-2" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4v16m8-8H4" />
            </svg>
            New Project
        </a>
    </div>

    <!-- Projects Grid -->
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($projects as $project)
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all group">
                <a href="{{ route('projects.show', $project) }}">
                    <!-- Project Title -->
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition">
                            {{ $project->name }}
                        </h3>
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-50 text-blue-600">
                            {{ $project->members->count() }} Members
                        </span>
                    </div>

                    <!-- Project Description -->
                    <p class="mt-3 text-sm text-gray-600 line-clamp-3">{{ $project->description }}</p>

                    <!-- Members -->
                    <div class="mt-5 flex -space-x-3">
                        @foreach($project->members->take(4) as $member)
                            <img src="https://ui-avatars.com/api/?name={{ $member->name }}" 
                                 class="w-10 h-10 rounded-full border-2 border-white shadow-md hover:scale-105 transition"
                                 title="{{ $member->name }}">
                        @endforeach
                        @if($project->members->count() > 4)
                            <span class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 text-xs font-medium text-gray-700 border-2 border-white">
                                +{{ $project->members->count() - 4 }}
                            </span>
                        @endif
                    </div>
                </a>

                <!-- Admin Actions -->
                @role('admin')
                    <div class="mt-6 flex space-x-3">
                        <!-- Edit -->
                        <a href="{{ route('projects.edit', $project->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-yellow-400 text-yellow-900 text-sm font-medium rounded-lg shadow hover:bg-yellow-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-4 w-4 mr-1" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5h2m-1 0v14m9-9H4" />
                            </svg>
                            Edit
                        </a>

                        <!-- Delete -->
                        <button wire:click="delete({{ $project->id }})" 
                                onclick="return confirm('Are you sure you want to delete this project?')" 
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg shadow hover:bg-red-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-4 w-4 mr-1" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Delete
                        </button>
                    </div>
                @endrole
            </div>
        @empty
            <div class="col-span-full text-center py-14">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-14 w-14 mx-auto mb-4 text-gray-400" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 13h6m-3-3v6m-9 5V5a2 2 0 012-2h6l2 2h6a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <p class="text-gray-500 font-medium">No projects found</p>
            </div>
        @endforelse
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mt-8 flex items-center bg-green-50 text-green-800 px-5 py-3 rounded-xl shadow border border-green-200">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 mr-2 text-green-600" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-8 flex items-center bg-red-50 text-red-700 px-5 py-3 rounded-xl shadow border border-red-200">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 mr-2 text-red-600" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif
</div>
