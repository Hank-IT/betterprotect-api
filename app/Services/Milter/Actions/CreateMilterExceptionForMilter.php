<?php

namespace App\Services\Milter\Actions;

use App\Services\Milter\Models\MilterException;
use App\Services\Order\Actions\FixItemOrder;

class CreateMilterExceptionForMilter
{
    public function __construct(
        protected CreateMilterException $createMilterException,
        protected SyncMilterExceptionsWithMilters $syncMilterExceptionsWIthMilters,
        protected FixItemOrder $fixItemOrder,
    ) {}

    public function execute(
        string $clientType,
        string $clientPayload,
        array $milterIds,
        ?string $description = null,
    ): MilterException {
        $model = $this->createMilterException->execute(
            $clientType,
            $clientPayload,
            $description,
        );

        $this->fixItemOrder->execute($model);

        $this->syncMilterExceptionsWIthMilters->execute($model, $milterIds);

        return $model;
    }
}
