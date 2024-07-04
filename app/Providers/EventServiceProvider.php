<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Services\Tasks\Events\TaskStarted::class => [
            \App\Services\Tasks\Listeners\TransitionTaskToStarted::class,
        ],
        \App\Services\Tasks\Events\TaskCreated::class => [
            \App\Services\Tasks\Listeners\CreateTask::class,
        ],
        \App\Services\Tasks\Events\TaskFailed::class => [
            \App\Services\Tasks\Listeners\TransitionTaskToFailed::class,
        ],
        \App\Services\Tasks\Events\TaskFinished::class => [
            \App\Services\Tasks\Listeners\TransitionTaskToFinished::class,
        ],
        \App\Services\Tasks\Events\TaskProgress::class => [
            \App\Services\Tasks\Listeners\CreateProgress::class,
        ],
    ];
}
