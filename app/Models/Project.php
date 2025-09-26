<?php

namespace App\Models;

use App\Models\Column;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Board;
use App\Models\ActivityLog;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'visibility',
    ];

   
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
 
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role_in_project')
            ->withTimestamps();
    }

    public function member()
{
    return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
}
 
    public function users()
    {
        return $this->members();
    }

   
    public function boards()
    {
        return $this->hasMany(Board::class);
    }
 
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    
    public function managers()
    {
        return $this->members()->wherePivot('role_in_project', 'manager');
    }
 
public function tasks()
{
    return $this->hasManyThrough(
        Task::class,  
        Column::class,  
        'board_id',  
        'column_id', 
        'id',  
        'id'  
    );
}

}
