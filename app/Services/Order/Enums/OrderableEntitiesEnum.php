<?php

namespace App\Services\Order\Enums;

use App\Services\Milter\Models\MilterException;
use App\Services\Order\Contracts\Orderable;
use App\Services\Rules\Models\ClientSenderAccess;

enum OrderableEntitiesEnum: string
{
    case CLIENT_SENDER_ACCESS = 'client-sender-access';
    case MILTER_EXCEPTION = 'milter-exception';

    protected function getModel(): string
    {
        return match($this)
        {
            OrderableEntitiesEnum::CLIENT_SENDER_ACCESS => ClientSenderAccess::class,
            OrderableEntitiesEnum::MILTER_EXCEPTION => MilterException::class,
        };
    }

    public function find(string|int $id): Orderable
    {
        return app($this->getModel())->find($id);
    }
}
