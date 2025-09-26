<div class="p-6 space-y-10 bg-gray-50 min-h-screen font-sans">

    {{-- 🌟 Welcome Banner --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-xl p-8 text-white">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
        <h1 class="text-3xl md:text-4xl font-bold"> Welcome Back, {{ Auth::user()->name ?? 'Contributor' }}</h1>
        <p class="mt-2 text-white/80">Here’s an overview of your contributions and progress.</p>
        <span class="inline-block mt-4 px-4 py-1 bg-white/20 rounded-full text-sm font-medium">
            Contributor Dashboard
        </span>
    </div>

    {{-- 📊 Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 shadow-lg rounded-2xl p-6 text-center text-white transform hover:scale-105 transition duration-300">
            <h3 class="text-sm font-medium opacity-80">Assigned Projects</h3>
            <p class="text-4xl font-bold mt-2">{{ $projectsCount }}</p>
        </div>
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 shadow-lg rounded-2xl p-6 text-center text-white transform hover:scale-105 transition duration-300">
            <h3 class="text-sm font-medium opacity-80">My Tasks</h3>
            <p class="text-4xl font-bold mt-2">{{ $tasksCount }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg rounded-2xl p-6 text-center text-white transform hover:scale-105 transition duration-300">
            <h3 class="text-sm font-medium opacity-80">Completed Tasks</h3>
            <p class="text-4xl font-bold mt-2">{{ $completedTasks }}</p>
        </div>
    </div>

    {{-- 📊 Charts --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Task Status --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition duration-300">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-indigo-500 rounded-full"></span> Task Status Overview
            </h3>
            <canvas id="taskStatusChart"></canvas>
        </div>

        {{-- Project Progress --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition duration-300">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span> Project Progress
            </h3>
            <canvas id="projectProgressChart"></canvas>
        </div>
    </div>

    {{-- 📝 Recent Items --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Recent Projects --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition duration-300">
            <h3 class="text-lg font-semibold mb-4">Recent Projects</h3>
            <ul class="space-y-3">
                @forelse($recentProjects as $proj)
                    <li class="p-3 bg-gray-50 rounded-md hover:bg-indigo-50 transition">
                        <p class="font-medium text-gray-800">{{ $proj->name }}</p>
                        <span class="text-xs text-gray-500">{{ $proj->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">No recent projects.</li>
                @endforelse
            </ul>
        </div>

        {{-- Recent Tasks --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition duration-300">
            <h3 class="text-lg font-semibold mb-4">Recent Tasks</h3>
            <ul class="space-y-3">
                @forelse($recentTasks as $task)
                    <li class="p-3 bg-gray-50 rounded-md flex justify-between items-center hover:bg-blue-50 transition">
                        <span class="font-medium">{{ $task->title }}</span>
                        <span class="text-xs px-3 py-1 rounded-full font-semibold
                            {{ $task->status === 'done' ? 'bg-green-100 text-green-700' : 
                               ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-600') }}">
                            {{ ucfirst($task->status) }}
                        </span>
                    </li>
                @empty
                    <li class="text-gray-500">No recent tasks.</li>
                @endforelse
            </ul>
        </div>

        {{-- Recent Activities --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition duration-300">
            <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
            <ul class="space-y-3">
                @forelse($recentActivities as $log)
                    <li class="p-3 bg-gray-50 rounded-md hover:bg-yellow-50 transition">
                        <p class="text-gray-700 text-sm">{{ $log->description }}</p>
                        <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">No activities logged.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

{{-- 📊 Charts Script --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Task Status Pie Chart
    new Chart(document.getElementById('taskStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'In Progress', 'Done'],
            datasets: [{
                data: [{{ $taskStatusData['draft'] }}, {{ $taskStatusData['in_progress'] }}, {{ $taskStatusData['done'] }}],
                backgroundColor: ['#9CA3AF', '#3B82F6', '#10B981']
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Project Progress Bar Chart
    new Chart(document.getElementById('projectProgressChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($projectProgressData['labels']) !!},
            datasets: [{
                label: 'Progress %',
                data: {!! json_encode($projectProgressData['values']) !!},
                backgroundColor: '#6366F1',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { stepSize: 20 } }
            }
        }
    });
</script>
@endpush
