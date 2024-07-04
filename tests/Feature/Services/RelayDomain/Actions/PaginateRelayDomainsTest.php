<?php

namespace Tests\Feature\Services\RelayDomain\Actions;

use App\Services\Recipients\Actions\PaginateRecipients;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\RelayDomains\Actions\PaginateRelayDomains;
use App\Services\RelayDomains\Models\RelayDomain;
use Tests\TestCase;

class PaginateRelayDomainsTest extends TestCase
{
    public function test()
    {
        RelayDomain::factory()->count(10)->create();

        $result = app(PaginateRelayDomains::class)->execute(1, 5);

        $this->assertCount(5, $result->toArray()['data']);
    }

    public function testWithSearch()
    {
        RelayDomain::factory()->count(10)->create();

        RelayDomain::factory()->create([
            'domain' => 'contoso.com'
        ]);

        $result = app(PaginateRelayDomains::class)->execute(1, 5, 'contoso.com');

        $this->assertCount(1, $result->toArray()['data']);
    }
}
