<?php

namespace App\Models;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['task_id','user_id','path','original_name','size','mime'];

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

