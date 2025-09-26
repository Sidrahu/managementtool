<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name, $description;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => Auth::id(),
        ]);

        $project->members()->attach(Auth::id(), ['role_in_project' => 'manager']);

        // Activity Log
        activity()->causedBy(Auth::user())->performedOn($project)
            ->withProperties(['name' => $project->name])
            ->log('project_created');

        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.projects.create')
         ->layout('layouts.app');
    }
}
