<div class="p-6 space-y-10 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 rounded-2xl shadow-lg">
        <div class="flex items-center gap-2">
            <!-- Lightning SVG -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        </div>
        <p class="text-sm opacity-90 mt-1 md:mt-0">Last updated: {{ now()->format('d M Y, h:i A') }}</p>

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
            <!-- Users -->
            <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl backdrop-blur border border-white/20 transition text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                </svg>
                Manage Users
            </a>

            <!-- Projects -->
            <a href="{{ route('admin.projects') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl backdrop-blur border border-white/20 transition text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 7v14h18V7H3z"/>
                    <path d="M7 3h10v4H7V3z"/>
                </svg>
                Manage Projects
            </a>

            <!-- Project Boards -->
            <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl backdrop-blur border border-white/20 transition text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                </svg>
                Project Boards
            </a>

            <!-- Refresh -->
            <button wire:click="$refresh" class="px-4 py-2 bg-white text-blue-600 rounded-xl hover:bg-blue-50 transition text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4v6h6"/>
                    <path d="M20 20v-6h-6"/>
                    <path d="M4 20a16 16 0 0 0 16-16"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mt-6">
        <!-- Users -->
        <div class="bg-white shadow-lg rounded-2xl p-5 hover:shadow-xl transition cursor-pointer"
             onclick="window.location='{{ route('admin.users') }}'">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                    </svg>
                    Users
                </span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">Total</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold text-blue-600">{{ $totalUsers }}</div>
        </div>

        <!-- Projects -->
        <div class="bg-white shadow-lg rounded-2xl p-5 hover:shadow-xl transition cursor-pointer"
             onclick="window.location='{{ route('admin.projects') }}'">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7v14h18V7H3z"/>
                        <path d="M7 3h10v4H7V3z"/>
                    </svg>
                    Projects
                </span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Total</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold text-green-600">{{ $totalProjects }}</div>
        </div>

        <!-- Storage -->
        <div class="bg-white shadow-lg rounded-2xl p-5 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M3 9h18"/>
                        <path d="M9 21V9"/>
                    </svg>
                    Storage
                </span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700">Used</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold text-purple-600">{{ $storage }}</div>
        </div>

        <!-- Tasks Overview -->
        <div class="bg-white shadow-lg rounded-2xl p-5 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4"/>
                        <path d="M21 12c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z"/>
                    </svg>
                    Tasks
                </span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-amber-100 text-amber-700">Overview</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold text-amber-600">{{ $totalTasks }}</div>
            <div class="mt-2 text-xs text-gray-500 space-y-1">
                <p class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed: 
                    <a href="{{ $this->getTasksLink('completed') }}" class="font-semibold text-green-600 underline">{{ $completedTasks }}</a>
                </p>
                <p class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12h18"/>
                        <path d="M12 3v18"/>
                    </svg>
                    In Progress: 
                    <a href="{{ $this->getTasksLink('in-progress') }}" class="font-semibold text-blue-600 underline">{{ $inProgressTasks }}</a>
                </p>
                <p class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Draft: 
                    <a href="{{ $this->getTasksLink('draft') }}" class="font-semibold text-gray-600 underline">{{ $draftTasks }}</a>
                </p>
            </div>
        </div>

        <!-- Overdue -->
        <div class="bg-white shadow-lg rounded-2xl p-5 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                    Tasks
                </span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-rose-100 text-rose-700">Overdue</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold text-rose-600">{{ $overdueTasks }}</div>
        </div>

        <!-- Active Users -->
        <div class="bg-white shadow-lg rounded-2xl p-5 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                        <path d="M6 20v-2a6 6 0 0 1 12 0v2"/>
                    </svg>
                    Active Users
                </span>
                <span class="px-2 py-0.5 text-xs rounded-full bg-sky-100 text-sky-700">30d</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold text-sky-600">{{ $activeUsers ?? '—' }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 17h2v-6h-2z"/>
                    <path d="M4 17h2v-4H4z"/>
                    <path d="M18 17h2v-8h-2z"/>
                </svg>
                Users Growth
            </h2>
            <livewire:charts.users-growth :range="$range" />
        </div>
        <div class="bg-white shadow-lg rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="M3 9h18"/>
                    <path d="M9 21V9"/>
                </svg>
                Tasks by Status
            </h2>
            <livewire:charts.tasks-status />
        </div>
    </div>

    <!-- Projects Boards Shortcut -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                </svg>
                Project Boards
            </h2>
            <a href="{{ route('projects.index') }}" class="text-sm text-blue-600 hover:underline">View all →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($projectsWithBoards as $project)
            <div class="p-4 border rounded-xl hover:shadow-lg transition cursor-pointer"
                 onclick="window.location='{{ route('projects.show', $project) }}'">
                <h3 class="font-semibold text-gray-800">{{ $project->name }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ $project->boards_count }} Boards</p>
            </div>
            @endforeach

            @if($projectsWithBoards->isEmpty())
                <p class="text-sm text-gray-500">No projects yet.</p>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white shadow-lg rounded-2xl p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 8v4l3 3"/>
                <circle cx="12" cy="12" r="10"/>
            </svg>
            Recent Activity
        </h2>
        <ul class="divide-y divide-gray-200">
            @forelse($recentActivities as $activity)
                <li class="py-4 flex justify-between items-center hover:bg-gray-50 px-2 rounded-lg transition">
                    <div>
                        <p class="text-sm text-gray-800">
                            <span class="font-semibold text-blue-600">{{ $activity->user->name }}</span>
                            <span class="text-gray-500"> {{ $activity->action }} </span>
                            <span class="font-semibold text-indigo-600">{{ $activity->subject }}</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </li>
            @empty
                <li class="py-3 text-sm text-gray-500 text-center">No recent activity</li>
            @endforelse
        </ul>
    </div>

</div>
