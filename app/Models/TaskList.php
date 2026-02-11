<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = ['name', 'position', 'board_id'];

    public function board() {
        return $this->belongsTo(Board::class);
    }

    public function cards() {
        return $this->hasMany(Card::class)->orderBy('position');
    }
}
