<?php

namespace Tests\Feature\Services\Server\Dtos;

use App\Services\Server\dtos\ServerStateCheckResult;
use Carbon\Carbon;
use Tests\TestCase;

class ServerStateCheckResultTest extends TestCase
{
    public function test()
    {
        $check = new ServerStateCheckResult(true, Carbon::now(), 'test');

        $this->assertTrue($check->getAvailable());
        $this->assertEquals('test', $check->getDescription());

        $array = $check->toArray();

        $this->assertTrue($array['available']);
        $this->assertEquals('test', $array['description']);
    }
}
