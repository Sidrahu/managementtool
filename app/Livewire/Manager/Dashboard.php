<?php

namespace App\Livewire\Manager;

use Livewire\Component;
use App\Models\{Project, Board, Column, Task, ActivityLog};
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public int $projectsCount = 0;
    public int $boardsCount = 0;
    public int $tasksCount = 0;

    public $recentProjects;         
    public $recentBoards;         
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

        
        $projects = Project::with('boards:id,project_id')
            ->whereHas('members', fn($q) => $q->where('user_id', $userId))
            ->latest('created_at')
            ->get(['id','name','description','created_at']);

        $this->projectsCount = $projects->count();
        $projectIds = $projects->pluck('id');

        
        $this->boardsCount = Board::whereIn('project_id', $projectIds)->count();

        
        $this->tasksCount = Task::whereHas('column.board', fn($q) => $q->whereIn('project_id', $projectIds))->count();

       
        $this->recentProjects   = $projects->take(5);
        $this->recentBoards     = Board::whereIn('project_id', $projectIds)->latest()->take(5)->get();
        $this->recentTasks      = Task::whereHas('column.board', fn($q) => $q->whereIn('project_id', $projectIds))
                                      ->latest()->take(5)->get();
        $this->recentActivities = ActivityLog::where('user_id', $userId)->latest()->take(10)->get();

       
        $statusCounts = Task::selectRaw('status, COUNT(*) as cnt')
            ->whereHas('column.board', fn($q) => $q->whereIn('project_id', $projectIds))
            ->groupBy('status')
            ->pluck('cnt', 'status');

        $this->taskStatusData = [
            'draft'       => (int) ($statusCounts['draft'] ?? 0),
            'in_progress' => (int) ($statusCounts['in_progress'] ?? 0),
            'done'        => (int) ($statusCounts['done'] ?? 0),
        ];

         
        $topIds = $this->recentProjects->pluck('id');

        $totalsByProject = Task::join('columns','columns.id','=','tasks.column_id')
            ->join('boards','boards.id','=','columns.board_id')
            ->whereIn('boards.project_id', $topIds)
            ->selectRaw('boards.project_id,
                        COUNT(tasks.id) as total,
                        SUM(CASE WHEN tasks.status = "done" THEN 1 ELSE 0 END) as done')
            ->groupBy('boards.project_id')
            ->get()
            ->keyBy('project_id');

        $labels = [];
        $values = [];
        foreach ($this->recentProjects as $proj) {
            $row = $totalsByProject[$proj->id] ?? null;
            $total = (int) ($row->total ?? 0);
            $done  = (int) ($row->done ?? 0);
            $labels[] = $proj->name;
            $values[] = $total ? round(($done / $total) * 100, 2) : 0;
        }
        $this->projectProgressData = ['labels' => $labels, 'values' => $values];
    }

    public function render()
    {
        return view('livewire.manager.dashboard')->layout('layouts.app');
    }
}
