<?php

namespace App\Livewire\Charts;

use Livewire\Component;
use App\Models\User;

class UsersGrowth extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        
        $users = User::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        
        $this->labels = collect(range(1, 12))
            ->map(fn($m) => now()->month($m)->format('M'))
            ->toArray();

        
        $this->data = collect(range(1, 12))
            ->map(fn($m) => $users[$m] ?? 0)
            ->toArray();
    }

    public function render()
    {
        return view('livewire.charts.users-growth');
    }
}
