<?php

namespace App\Services;

use App\Events\Task;
use App\Models\User;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Task as TaskModel;

class ViewTask
{
    protected $startDate;

    protected $endDate;

    protected $errors;

    protected $id;

    protected $username;

    protected $task;

    public function __construct($username, $message, $task)
    {
        $this->startDate = Carbon::now();

        $this->username = $username;

        $this->task = $task;

        $this->id = (Uuid::uuid1())->toString();

        TaskModel::firstOrCreate([
            'id' => $this->id,
            'message' => $message,
            'task' => $task,
            'username' => $this->username,
            'startDate' => $this->startDate,
            'status' => Task::STATUS_RUNNING,
        ]);

        event(new Task($this->id, $this->task, $message, $this->username, $this->startDate->toDateTimeString()));
    }

    public static function fromModel(TaskModel $task)
    {
        return new static($task->username, $task->message, $task->task);
    }

    public function update($message)
    {
        optional(TaskModel::find($this->id))->update([
            'message' => $message,
        ]);

        event(new Task($this->id, $this->task, $message, $this->username, $this->startDate->toDateTimeString()));
    }

    public function finishedSuccess($message)
    {
        $this->endDate = Carbon::now();

        optional(TaskModel::find($this->id))->update([
            'message' => $message,
            'endDate' => $this->endDate,
            'status' => Task::STATUS_FINISHED,
        ]);

        event(new Task($this->id, $this->task, $message, $this->username, $this->startDate->toDateTimeString(), $this->endDate->toDateTimeString(), Task::STATUS_FINISHED));
    }

    public function finishedError($message)
    {
        $this->endDate = Carbon::now();

        optional(TaskModel::find($this->id))->update([
            'message' => $message,
            'endDate' => $this->endDate,
            'status' => Task::STATUS_ERROR,
        ]);

        event(new Task($this->id, $this->task, $message, $this->username, $this->startDate->toDateTimeString(), $this->endDate->toDateTimeString(), Task::STATUS_ERROR));
    }
}