<?php

namespace App\Services\Tasks\Enums;

enum TaskStatusEnum: int
{
    case RUNNING = 1;
    case ERROR = 2;
    case FINISHED = 3;
    case QUEUED = 4;
}
