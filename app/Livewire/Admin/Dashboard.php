<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Board;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Component
{
    public $totalTasks;
    public $recentProjects;
    public string $q = '';
    public string $range = '30'; 
    public int $totalUsers = 0;
    public int $totalProjects = 0;
    public string $storage = '0 B';
    public string $attachmentsSize = '0 B';
    public string $otherFilesSize = '0 B';

     
    public ?int $activeUsers = null;  
    public int $overdueTasks = 0;
    public int $dueSoonTasks = 0;
    public int $completedTasks = 0;
    public int $inProgressTasks = 0;
    public int $draftTasks = 0;

     
    public $recentActivities;

    
    public $usersGrowth;
    public $tasksByStatus;

     
    public $projectsWithBoards;

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentActivities();
        $this->loadCharts();
        $this->loadProjectsBoards();
    }

    public function updatedRange()
    {
        $this->loadCharts();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }

    
public function getTasksLink($status = null)
{
     
    if(!$status) return route('projects.index');

    
    $firstBoard = \App\Models\Board::first();
    if(!$firstBoard) return route('projects.index');

    return route('projects.boards.kanban', ['board' => $firstBoard->id]);
}


    /** -------- Data Loaders ---------- */
    private function loadStats(): void
    {
         
        $this->totalUsers    = User::count();
        $this->totalProjects = Project::count();

        
        $totalBytes = $this->getStorageUsage();
        $this->storage = $this->formatBytes($totalBytes);

         
        [$attach, $other] = $this->getStorageBreakdown();
        $this->attachmentsSize = $this->formatBytes($attach);
        $this->otherFilesSize  = $this->formatBytes($other);

        
        if (Schema::hasColumn('users', 'last_login_at')) {
            $this->activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();
        } else {
            $this->activeUsers = null;
        }

         
        $this->completedTasks = Task::whereIn('status', ['done', 'completed'])->count();
        $this->inProgressTasks = Task::where('status', 'in-progress')->count();
        $this->draftTasks = Task::where('status', 'draft')->count();

        $this->overdueTasks = Task::whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['done', 'completed'])
            ->count();

        $this->dueSoonTasks = Task::whereNotNull('due_date')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->count();
    }

    private function loadRecentActivities(): void
    {
        $this->recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(8)
            ->get();
    }

    private function loadCharts(): void
    {
        
        $users = User::query();
        if ($this->range !== 'all') {
            $users->where('created_at', '>=', now()->subDays((int) $this->range));
        }
        $this->usersGrowth = $users
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m") as ym')
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('count', 'ym')
            ->toArray();

        
        $this->tasksByStatus = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function loadProjectsBoards(): void
    {
        $this->projectsWithBoards = Project::withCount('boards')
            ->with('boards:id,project_id,name')
            ->latest()
            ->take(5)
            ->get();
    }

    /** -------- Helpers ---------- */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max((int) $bytes, 0);
        $pow   = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $pow   = min($pow, count($units) - 1);
        $bytes = $bytes / (1024 ** $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function getStorageUsage(): int
    {
        $size = 0;
        foreach (Storage::allFiles() as $file) {
            $size += (int) Storage::size($file);
        }
        return $size;
    }

    private function getStorageBreakdown(): array
    {
        $attachmentsBytes = 0;

        if (Schema::hasTable('attachments')) {
            if (Schema::hasColumn('attachments', 'size')) {
                $attachmentsBytes = \DB::table('attachments')->sum('size');
            } elseif (Schema::hasColumn('attachments', 'path')) {
                $paths = \DB::table('attachments')->pluck('path');
                foreach ($paths as $path) {
                    if (Storage::exists($path)) {
                        $attachmentsBytes += (int) Storage::size($path);
                    }
                }
            }
        }

        $total = $this->getStorageUsage();
        $other = max($total - $attachmentsBytes, 0);
        return [$attachmentsBytes, $other];
    }
}
