<!-- resources/views/manager/dashboard.blade.php -->

<!-- resources/views/manager/dashboard.blade.php -->
<div x-data="{ darkMode: false }" :class="{ 'dark bg-gray-900 text-white': darkMode }" class="p-6 min-h-screen transition font-sans">

    <!-- 🌟 Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-pink-500 rounded-2xl shadow-xl p-8 text-white mb-10">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
        <h1 class="text-3xl md:text-4xl font-bold flex items-center gap-2">
            <!-- 👨‍💼 Manager Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.002 9.002 0 0112 15a9.002 9.002 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Welcome Back, {{ auth()->user()->name ?? 'Manager' }}
        </h1>
        <p class="mt-2 text-white/80">Here’s your project overview and recent updates.</p>
        <span class="inline-block mt-4 px-4 py-1 bg-white/20 rounded-full text-sm font-medium">
            Manager Dashboard
        </span>
    </div>

    <!-- Actions Row (Dark Mode + Buttons) -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <div></div>
        <div class="flex space-x-3 items-center mt-4 md:mt-0">
            <!-- Dark mode toggle -->
            <button @click="darkMode = !darkMode"
                class="px-3 py-2 rounded-xl shadow bg-gray-200 dark:bg-gray-700 hover:shadow-lg transition flex items-center space-x-2">
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-12.34l-.7.7M4.04 19.96l.7-.7M21 12h1M2 12H1m16.95 4.95l.7.7M4.34 4.34l.7.7"/>
                </svg>
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 00-9 9 9 9 0 0015.9 5.5A9 9 0 0012 3z"/>
                </svg>
                <span x-show="!darkMode">Dark</span>
                <span x-show="darkMode">Light</span>
            </button>
            <a href="{{ route('projects.create') }}" 
               class="px-5 py-2 bg-blue-600 text-white font-medium rounded-xl shadow hover:bg-blue-700 hover:shadow-lg transition">
               + New Project
            </a>
            <a href="{{ route('projects.index') }}" 
               class="px-5 py-2 bg-gray-200 dark:bg-gray-700 dark:text-white text-gray-800 font-medium rounded-xl shadow hover:bg-gray-300 hover:shadow-lg transition">
               All Projects
            </a>
        </div>
    </div>


    <!-- Search + Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-3 md:space-y-0">
        <div class="relative w-full md:w-1/3">
            <input type="text" placeholder="Search projects or boards..." 
                class="w-full pl-10 pr-4 py-2 border rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring focus:ring-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </div>
        <select class="px-4 py-2 border rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
            <option>All Time</option>
            <option>This Week</option>
            <option>This Month</option>
        </select>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Projects -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl shadow-lg p-6 flex flex-col">
            <div class="flex items-center justify-between">
                <p class="text-sm opacity-80">Projects</p>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-lg flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    <span>12%</span>
                </span>
            </div>
            <p class="text-3xl font-bold mt-2">{{ $projectsCount }}</p>
            <canvas id="projectsSpark" class="h-12 mt-3"></canvas>
        </div>

        <!-- Boards -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-2xl shadow-lg p-6 flex flex-col">
            <div class="flex items-center justify-between">
                <p class="text-sm opacity-80">Boards</p>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-lg flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    <span>7%</span>
                </span>
            </div>
            <p class="text-3xl font-bold mt-2">{{ $boardsCount }}</p>
            <canvas id="boardsSpark" class="h-12 mt-3"></canvas>
        </div>

        <!-- Tasks -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-2xl shadow-lg p-6 flex flex-col">
            <div class="flex items-center justify-between">
                <p class="text-sm opacity-80">Tasks</p>
                <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-lg flex items-center space-x-1">
                    <svg class="w-3 h-3 transform rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    <span>3%</span>
                </span>
            </div>
            <p class="text-3xl font-bold mt-2">{{ $tasksCount }}</p>
            <canvas id="tasksSpark" class="h-12 mt-3"></canvas>
        </div>

        <!-- Quick Actions -->
        <a href="{{ route('projects.create') }}" 
           class="bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-2xl shadow-lg p-6 flex flex-col hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <p class="text-sm opacity-80">Quick Actions</p>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mt-2">+</p>
        </a>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Task Status Overview</h2>
            <canvas id="taskStatusChart" class="w-full h-60"></canvas>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Projects Progress</h2>
            <canvas id="projectProgressChart" class="w-full h-60"></canvas>
        </div>
    </div>

    <!-- Boards & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Boards -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Available Boards</h2>
            @if($recentBoards->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No boards available.</p>
            @else
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recentBoards as $board)
                        <li class="py-3 px-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium">{{ $board->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Project: {{ $board->project->name ?? 'N/A' }}</p>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded mt-2">
                                        <div class="bg-green-500 h-2 rounded" style="width: {{ rand(30,90) }}%"></div>
                                    </div>
                                </div>
                                <a href="{{ route('projects.boards.kanban', $board->id) }}" 
                                   class="text-green-600 dark:text-green-400 hover:underline">View</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
            @if($recentActivities->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity yet.</p>
            @else
                <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                    @foreach($recentActivities as $log)
                        <li class="py-3 px-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition flex justify-between items-center">
                            <div>
                                <p class="text-sm">{{ $log->action }}</p>
                                <span class="text-xs text-gray-400">{{ $log->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <span class="px-2 py-1 rounded-lg text-xs font-medium flex items-center space-x-1
                                {{ str_contains($log->action, 'completed') ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                @if(str_contains($log->action, 'completed'))
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Done</span>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4"/></svg>
                                    <span>Update</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const status = @json($taskStatusData);
    const proj = @json($projectProgressData);

    // Donut chart
    new Chart(document.getElementById('taskStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'In Progress', 'Done'],
            datasets: [{
                data: [status.draft, status.in_progress, status.done],
                backgroundColor: ['#60A5FA', '#FBBF24', '#34D399'],
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Bar chart
    new Chart(document.getElementById('projectProgressChart'), {
        type: 'bar',
        data: {
            labels: proj.labels,
            datasets: [{
                label: 'Completed %',
                data: proj.values,
                backgroundColor: '#6366F1'
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } }
    });

    // Sparklines
    const sparkOptions = { responsive: true, plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { display: false } } };
    new Chart(document.getElementById('projectsSpark'), { type: 'line', data: { labels: [1,2,3,4,5], datasets: [{ data: [2,4,3,6,5], borderColor: '#fff' }] }, options: sparkOptions });
    new Chart(document.getElementById('boardsSpark'), { type: 'line', data: { labels: [1,2,3,4,5], datasets: [{ data: [1,2,1,3,2], borderColor: '#fff' }] }, options: sparkOptions });
    new Chart(document.getElementById('tasksSpark'), { type: 'line', data: { labels: [1,2,3,4,5], datasets: [{ data: [5,4,6,3,4], borderColor: '#fff' }] }, options: sparkOptions });
</script>
@endpush
