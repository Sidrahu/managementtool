<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;

class Edit extends Component
{
    public $projectId;
    public $name;
    public $description;

    public function mount(Project $project)
    {
        $this->projectId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::findOrFail($this->projectId);
        $project->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Project updated successfully!');
        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.projects.edit')
            ->layout('layouts.app');
    }
}
