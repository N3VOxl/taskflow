<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['title', 'description', 'position', 'due_date', 'task_list_id'];

    public function taskList() {
        return $this->belongsTo(TaskList::class);
    }
}