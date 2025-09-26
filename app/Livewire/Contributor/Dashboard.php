<?php

namespace App\Livewire\Contributor;

use Livewire\Component;
use App\Models\{Project, Board, Task, ActivityLog};
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public int $projectsCount = 0;
    public int $tasksCount = 0;
    public int $completedTasks = 0;

    public $recentProjects;        
    public $recentTasks;           
    public $recentActivities;      

    public array $taskStatusData = [];               
    public array $projectProgressData = [
        'labels' => [],
        'values' => [],
    ];

    public function mount()
    {
        $userId = Auth::id();

        // Fetch all projects assigned to user
        $projects = Project::whereHas('members', fn($q) => $q->where('user_id', $userId))
            ->latest('created_at')
            ->get(['id','name','description','created_at']);

        $this->projectsCount = $projects->count();

        // Fetch all tasks assigned or created by user
        $tasksQuery = Task::where(function($q) use ($userId) {
            $q->where('assignee_id', $userId)
              ->orWhere('created_by', $userId);
        });

        $this->tasksCount     = $tasksQuery->count();
        $this->completedTasks = (clone $tasksQuery)->where('status', 'done')->count();

        $this->recentProjects   = $projects->take(5);
        $this->recentTasks      = (clone $tasksQuery)->latest()->take(5)->get();
        $this->recentActivities = ActivityLog::where('user_id', $userId)->latest()->take(10)->get();

        // Task status counts
        $statusCounts = (clone $tasksQuery)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $this->taskStatusData = [
            'draft'       => $statusCounts['draft'] ?? 0,
            'in_progress' => $statusCounts['in_progress'] ?? 0,
            'done'        => $statusCounts['done'] ?? 0,
        ];

        // Project Progress Data
        $labels = [];
        $values = [];

        foreach ($projects as $proj) { // use all projects
            $total = Task::whereHas('column.board', fn($q) => $q->where('project_id', $proj->id))
                         ->count();

            $done = Task::where('status','done')
                        ->whereHas('column.board', fn($q) => $q->where('project_id', $proj->id))
                        ->count();

            $labels[] = $proj->name;
            $values[] = $total ? round(($done / $total) * 100, 2) : 0;
        }

        $this->projectProgressData = ['labels' => $labels, 'values' => $values];
    }

    public function render()
    {
        return view('livewire.contributor.dashboard')->layout('layouts.app');
    }
}
