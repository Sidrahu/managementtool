<?php

namespace App\Models;
use App\Models\Project;
use App\Models\Task;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
  use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
 

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_admin',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ownedProjects() {
    return $this->hasMany(Project::class, 'owner_id');
}

public function projects() {
        return $this->belongsToMany(Project::class, 'project_user')
                    ->withPivot('role_in_project')
                    ->withTimestamps();
    }


public function tasks() {
    return $this->belongsToMany(Task::class, 'task_assignments');
}
public function isAdmin()
{
    return $this->is_admin;
}

 

}
