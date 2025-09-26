<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $projects;
    protected $listeners = ['refreshBoards' => '$refresh'];

    public function mount()
    {
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = Auth::user()->hasRole('admin')
            ? Project::with('members')->latest()->get()
            : Auth::user()->projects()->with('members')->latest()->get();
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);

         
        if (Auth::user()->hasRole('admin') || $project->user_id == Auth::id()) {
            $project->delete();
            session()->flash('message', 'Project deleted successfully ✅');
        } else {
            session()->flash('error', 'Unauthorized ❌');
        }

        $this->loadProjects();
    }

    public function render()
    {
        return view('livewire.projects.index')
            ->layout('layouts.app');
    }
}
