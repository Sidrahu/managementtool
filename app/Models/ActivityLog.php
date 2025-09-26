<?php

namespace App\Models;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ActivityLog extends Model
{
 
    use HasFactory;

    protected $fillable = [
        'project_id', 'task_id', 'user_id', 'action', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

     

    public function project() {
    return $this->belongsTo(Project::class);
}
public function task() {
    return $this->belongsTo(Task::class);
}
public function user() {
    return $this->belongsTo(User::class);
}

}
