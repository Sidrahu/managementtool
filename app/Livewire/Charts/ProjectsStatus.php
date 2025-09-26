<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use App\Models\Project;

class ProjectsStatus extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        $statuses = Project::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $this->labels = array_keys($statuses);
        $this->data = array_values($statuses);
    }

    public function render()
    {
        return view('livewire.charts.projects-status');
    }
}
