<?php

namespace App\Livewire\Boards;

use Livewire\Component;
use App\Models\Project;
use App\Models\Board;
use Livewire\Attributes\On;

class Index extends Component
{
    public Project $project;
     
      public $status;
      public $assignee;
      public $priority;
        


    protected $listeners = [
        'task-updated' => '$refresh',
        'task-moved'   => '$refresh',
        'task-created' => '$refresh',
        'task-deleted' => '$refresh',
    ];

    #[On('boardReordered')]
    public function reorderBoards($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Board::where('id', $id)->update(['position' => $index + 1]);
        }
    }

    public function deleteBoard($id)
    {
        Board::findOrFail($id)->delete();
        session()->flash('message', 'Board deleted successfully!');
    }

     
    public function openColumnModal($boardId)
    {
        $this->dispatch('open-column-modal', ['boardId' => $boardId]);
    }
public function render()
{
     
    $boards = $this->project->boards()
        ->with(['tasks' => function($query) {
            $query->when($this->status, fn($q) => $q->where('status', $this->status))
                  ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
                  ->when($this->assignee, fn($q) => $q->whereHas('assignees', fn($q2) => $q2->where('user_id', $this->assignee)))
                  ->with('assignees');  
        }])
        ->orderBy('position')
        ->get();

    
    $boards->transform(function ($board) {
        $totalTasks = $board->tasks->count();
        $doneTasks  = $board->tasks->where('status', 'done')->count();

        $board->progress_percent = $totalTasks > 0
            ? round(($doneTasks / $totalTasks) * 100)
            : 0;

        return $board;
    });

    return view('livewire.boards.index', compact('boards'))
        ->layout('layouts.app');
}

}
