<?php

namespace App\Services\Tasks\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function taskProgresses()
    {
        return $this->hasMany(TaskProgress::class);
    }

    protected static function newFactory()
    {
        return TaskFactory::new();
    }
}
