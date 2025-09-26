<?php

namespace App\Livewire\Boards;

use Livewire\Component;
use App\Models\Task;
use App\Models\User;

class BoardTasks extends Component
{
    public $search = '';
    public $status = '';
    public $priority = '';
    public $assignee = '';

    public $users;

    public function mount()
    {
        
        $this->users = User::all();
    }

    public function render()
    {
        $tasks = Task::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', "%{$this->search}%")
                          ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
            ->when($this->assignee, fn($q) => 
                $q->whereHas('assignees', fn($q2) => $q2->where('user_id', $this->assignee))
            )
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('livewire.boards.board-tasks', [
            'tasks' => $tasks,
        ]);
    }
}
