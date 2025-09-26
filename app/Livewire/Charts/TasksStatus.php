<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use App\Models\Task;

class TasksStatus extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $rows = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count','status')
            ->toArray();

        // order preferences
        $preferred = ['draft','pending','in_progress','done','archived'];
        $ordered = [];
        foreach ($preferred as $s) {
            if (isset($rows[$s])) { $ordered[$s] = $rows[$s]; unset($rows[$s]); }
        }
        foreach ($rows as $k=>$v) $ordered[$k] = $v;

        $this->labels = array_keys($ordered);
        $this->data = array_values($ordered);
    }

    public function render()
    {
        return view('livewire.charts.tasks-status', [
            'labels' => $this->labels,
            'data' => $this->data,
        ]);
    }
}
