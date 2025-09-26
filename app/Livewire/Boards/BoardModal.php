<?php

namespace App\Livewire\Boards;

use Livewire\Component;
use App\Models\Board;
use App\Models\Project;

class BoardModal extends Component
{
    public $show = false;
    public $name = '';
    public $projectId;

    #[\Livewire\Attributes\On('open-board-modal')]
    public function open($projectId)
    {
        $this->projectId = $projectId;
        $this->show = true;
    }

    public function save()
    {
        $this->validate(['name' => 'required|string|max:255']);

        Board::create([
            'name' => $this->name,
            'project_id' => $this->projectId,
        ]);

        $this->reset(['name', 'show']);
        $this->dispatch('board-added');  
    }

    public function render()
    {
        return view('livewire.boards.board-modal')
        ->layout('layouts.app');
    }
}
