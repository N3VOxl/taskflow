<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = ['title', 'color', 'workspace_id'];

    public function workspace() {
        return $this->belongsTo(Workspace::class);
    }

    public function taskLists() {
        return $this->hasMany(TaskList::class);
    }
}
