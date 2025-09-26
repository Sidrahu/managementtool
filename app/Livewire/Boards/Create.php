<?php
namespace App\Livewire\Boards;

use Livewire\Component;
use App\Models\Board;
use App\Models\Project;

class Create extends Component
{
    public Project $project;
    public $name = '';
    public $open = false;

    public function save()
    {
        $this->validate(['name' => 'required|string|max:255']);

        Board::create([
            'project_id' => $this->project->id,
            'name' => $this->name,
            'position' => $this->project->boards()->count() + 1,
        ]);

        session()->flash('message', 'Board created successfully!');

        return redirect()->route('projects.boards.index', ['project' => $this->project->id]);
    }

    public function render()
    {
        return view('livewire.boards.create')->layout('layouts.app');
    }
}
