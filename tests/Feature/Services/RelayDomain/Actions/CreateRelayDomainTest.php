<?php

namespace Tests\Feature\Services\RelayDomain\Actions;

use App\Services\RelayDomains\Actions\CreateRelayDomain;
use App\Services\RelayDomains\Models\RelayDomain;
use Tests\TestCase;

class CreateRelayDomainTest extends TestCase
{
    public function test()
    {
        $data = RelayDomain::factory()->make();

        $model = app(CreateRelayDomain::class)->execute($data->domain);

        $this->assertModelExists($model);

        $this->assertDatabaseHas('relay_domains', [
            'domain' => $data->domain,
        ]);
    }
}
