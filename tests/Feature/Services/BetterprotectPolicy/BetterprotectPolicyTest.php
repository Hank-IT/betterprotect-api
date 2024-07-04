<?php

namespace Tests\Feature\Services\BetterprotectPolicy;

use App\Services\BetterprotectPolicy\BetterprotectPolicy;
use Tests\TestCase;

class BetterprotectPolicyTest extends TestCase
{
    public function test()
    {
        $policy = new BetterprotectPolicy;

        $this->assertIsArray($policy->get());
    }
}
