<?php

namespace App\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Concerns\SerializesDate;
use App\Events\Task as TaskEvent;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use SerializesDate;

    const STATUS_RUNNING = 1;
    const STATUS_ERROR = 2;
    const STATUS_FINISHED = 3;

    public $incrementing = false;

    protected $fillable = [
        'message',
        'task',
        'username',
        'startDate',
        'endDate',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $task->id = Uuid::uuid1()->toString();
            $task->startDate = Carbon::now();
        });

        static::created(function($task) {
            TaskEvent::dispatch($task);
        });

        static::updated(function($task) {
            TaskEvent::dispatch($task);
        });
    }
}
