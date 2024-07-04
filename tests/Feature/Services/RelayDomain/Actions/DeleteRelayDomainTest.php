<?php

namespace Tests\Feature\Services\RelayDomain\Actions;

use App\Services\RelayDomains\Actions\DeleteRelayDomain;
use App\Services\RelayDomains\Models\RelayDomain;
use Tests\TestCase;

class DeleteRelayDomainTest extends TestCase
{
    public function test()
    {
        $model = RelayDomain::factory()->create();

        $this->assertModelExists($model);

        app(DeleteRelayDomain::class)->execute($model);

        $this->assertModelMissing($model);

        $this->assertDatabaseMissing('relay_domains', [
            'domain' => $model->domain,
        ]);
    }
}
