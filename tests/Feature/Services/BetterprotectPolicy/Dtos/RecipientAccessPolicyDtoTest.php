<?php

namespace Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetMilterExceptions;
use App\Services\BetterprotectPolicy\Actions\GetRelayRecipients;
use App\Services\BetterprotectPolicy\Dtos\MilterExceptionPolicyDto;
use App\Services\BetterprotectPolicy\Dtos\RecipientAccessPolicyDto;
use Tests\TestCase;

class RecipientAccessPolicyDtoTest extends TestCase
{
    public function test()
    {
        $dto = new RecipientAccessPolicyDto;

        $this->assertInstanceOf(GetRelayRecipients::class, $dto->getDataRetriever());
        $this->assertEquals('Recipients are updating...', $dto->getDescription());
        $this->assertEquals('relay_recipients', $dto->getTable());
    }
}
