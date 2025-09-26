<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

     
    public function view(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || $project->members()->where('user_id', $user->id)->exists();
    }

    
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

     
    public function update(User $user, Project $project): bool
    {
        return $user->hasRole('admin') || $project->owner_id === $user->id;
    }

    
    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('admin') || $project->owner_id === $user->id;
    }

    
    public function restore(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }

    
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }

    
    public function manage(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || $project->members()
                ->where('user_id', $user->id)
                ->wherePivot('role_in_project', 'manager')
                ->exists();
    }

    
    public function contribute(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || $project->members()
                ->where('user_id', $user->id)
                ->wherePivotIn('role_in_project', ['manager', 'contributor'])
                ->exists();
    }

    
    public function viewOnly(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || $project->members()->where('user_id', $user->id)->exists();
    }
}
