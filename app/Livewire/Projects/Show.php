<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class Show extends Component
{
    public $project;

    public function mount(Project $project)
    {
        // Access check
        if (! Gate::allows('manage', $project)) {
            abort(403);
        }

        $this->project = $project->load('members', 'boards');
    }

    public function render()
    {
        return view('livewire.projects.show')
            ->layout('layouts.app');
    }
}
