<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;

class Projects extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 9;  

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $projects = Project::with('users')
            ->when($this->search, fn($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.projects', compact('projects'))
            ->layout('layouts.app');
    }
}
