<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Board;
use App\Models\Column;
use App\Models\User;
use App\Models\Comment;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'column_id', 'title', 'description', 'due_date',
        'status', 'priority', 'sort_order','assignee_id', 'created_by'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignments');
    }

    public function assignee()
{
    return $this->belongsTo(User::class, 'assignee_id');
}


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

     
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== 'done';
    }

   
    public function getIsDueTodayAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isToday()
            && $this->status !== 'done';
    }

  
    public function getIsUpcomingAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isFuture()
            && $this->status !== 'done';
    }

     

}
