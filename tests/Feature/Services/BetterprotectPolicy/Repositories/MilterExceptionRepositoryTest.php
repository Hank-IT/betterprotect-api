<?php

namespace Services\BetterprotectPolicy\Repositories;

use App\Services\BetterprotectPolicy\Repositories\MilterExceptionRepository;
use App\Services\Milter\Models\MilterException;
use Tests\TestCase;

class MilterExceptionRepositoryTest extends TestCase
{
    public function test()
    {
        $activeExceptions = MilterException::factory()->count(2)->create([
            'active' => true,
        ]);

        MilterException::factory()->count(2)->create([
            'active' => false,
        ]);

        $result = app(MilterExceptionRepository::class)->get();

        $this->assertCount(2, $result);

        $this->assertEquals($activeExceptions[0]->getKey(), $result[0]->getKey());
        $this->assertEquals($activeExceptions[1]->getKey(), $result[1]->getKey());
    }
}
