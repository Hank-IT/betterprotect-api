<?php

namespace Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetMilterExceptions;
use App\Services\BetterprotectPolicy\Dtos\MilterExceptionPolicyDto;
use Tests\TestCase;

class MilterExceptionPolicyDtoTest extends TestCase
{
    public function test()
    {
        $dto = new MilterExceptionPolicyDto;

        $this->assertInstanceOf(GetMilterExceptions::class, $dto->getDataRetriever());
        $this->assertEquals('Milter exceptions are updating...', $dto->getDescription());
        $this->assertEquals('milter_exceptions', $dto->getTable());
    }
}
