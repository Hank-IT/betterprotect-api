<?php

namespace App\Services\Activation\Enums;

use App\Services\Activation\Contracts\Activatable;
use App\Services\Milter\Models\MilterException;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\RelayDomains\Models\RelayDomain;
use App\Services\Rules\Models\ClientSenderAccess;
use App\Services\Transport\Models\Transport;

enum ActivatableEntitiesEnum: string
{
    case CLIENT_SENDER_ACCESS = 'client-sender-access';
    case MILTER_EXCEPTION = 'milter-exception';
    case RELAY_DOMAIN = 'relay-domain';
    case RELAY_RECIPIENT = 'relay-recipient';
    case TRANSPORT = 'transport';

    protected function getModel(): string
    {
        return match($this)
        {
            ActivatableEntitiesEnum::CLIENT_SENDER_ACCESS => ClientSenderAccess::class,
            ActivatableEntitiesEnum::MILTER_EXCEPTION => MilterException::class,
            ActivatableEntitiesEnum::RELAY_DOMAIN => RelayDomain::class,
            ActivatableEntitiesEnum::RELAY_RECIPIENT => RelayRecipient::class,
            ActivatableEntitiesEnum::TRANSPORT => Transport::class,
        };
    }

    public function find(string|int $id): Activatable
    {
        return app($this->getModel())->find($id);
    }
}
