<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class InviteMember extends Component
{
    public $project;
    public $email;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function invite()
    {
        $this->validate(['email' => 'required|email']);

        $user = User::where('email', $this->email)->first();
        if (!$user) {
            $this->addError('email', 'User not found.');
            return;
        }

        $this->project->members()->syncWithoutDetaching([
            $user->id => ['role_in_project' => 'contributor']
        ]);

        activity()->causedBy(Auth::user())->performedOn($this->project)
            ->withProperties(['member' => $user->email])
            ->log('member_added');

        $this->reset('email');
        session()->flash('message', 'Member invited successfully!');
    }

    public function render()
    {
        return view('livewire.projects.invite-member')
          ->layout('layouts.app');
    }
}
