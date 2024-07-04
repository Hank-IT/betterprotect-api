<?php

namespace App\Services\Order\Enums;

use App\Services\Order\Actions\MoveItemDown;
use App\Services\Order\Actions\MoveItemUp;

enum ModeEnum: string
{
    case UP = 'up';
    case DOWN = 'down';

    public function getAction()
    {
        return match($this)
        {
            ModeEnum::UP => app(MoveItemUp::class),
            ModeEnum::DOWN => app(MoveItemDown::class),
        };
    }
}
