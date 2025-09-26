<?php

namespace App\Models;
use App\Models\Project;
use App\Models\Column;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
 

class Board extends Model
{
     

      protected $fillable = ['project_id','name','position'];


    public function project() {
    return $this->belongsTo(Project::class);
}
public function columns() {
    return $this->hasMany(Column::class);
}

 public function tasks() {
        return $this->hasManyThrough(
            Task::class,     
            Column::class,    
            'board_id',      
            'column_id',      
            'id',             
            'id'              
        );
    }

    public function getProgressPercentAttribute(): int
{
    $total = $this->tasks()->count();
    if ($total === 0) {
        return 0;
    }
    $done = $this->tasks()->where('status', 'done')->count();
    return (int) round(($done / $total) * 100);
}

}
