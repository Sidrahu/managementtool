<?php

namespace App\Models;
use App\Models\Board;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Column extends Model
{
    use HasFactory;

    protected $fillable = ['board_id', 'name', 'position', 'wip_limit'];


    public function board() {
        return $this->belongsTo(Board::class);
     }
    public function tasks() {
        return $this->hasMany(Task::class)->orderBy('sort_order');
    }

}
