<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class Users extends Component
{
    public $users;

    public function mount()
    {
        
        $this->users = User::with('roles')->get();
    }

    public function render()
    {
        return view('livewire.admin.users')
            ->layout('layouts.app');
    }
}
