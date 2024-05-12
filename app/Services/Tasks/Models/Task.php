<?php

namespace App\Services\Tasks\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const STATUS_RUNNING = 1;
    const STATUS_ERROR = 2;
    const STATUS_FINISHED = 3;

    const STATUS_QUEUED = 4;

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
}
