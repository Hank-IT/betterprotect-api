<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Recipients\Actions\PaginateRecipients;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\RelayDomains\Actions\CreateRelayDomain;
use App\Services\RelayDomains\Actions\DeleteRelayDomain;
use App\Services\RelayDomains\Actions\PaginateRelayDomains;
use App\Services\RelayDomains\Models\RelayDomain;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\MockInterface;
use Tests\TestCase;

class RelayDomainControllerTest extends TestCase
{
    public function testIndex()
    {
        $user = User::factory()->create();

        $this->be($user);

        $relayDomain = RelayDomain::factory()->create();

        $paginator = new LengthAwarePaginator([
            $relayDomain
        ], 1, 5);

        $this->mock(PaginateRelayDomains::class, function(MockInterface $mock) use($paginator) {
            $mock->shouldReceive('execute')->once()->withArgs([
                1, 5, 'test',
            ])->andReturn($paginator);
        });

        $this->getJson(route('api.v1.relay-domain.index', [
            'page_number' => 1,
            'page_size' => 5,
            'search' => 'test'
        ]))->assertSuccessful()->assertJsonPath('data.0.id', $relayDomain->getKey());
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $this->be($user);

        $relayDomain = RelayDomain::factory()->create();

        $domain = fake()->domainName;

        $this->mock(CreateRelayDomain::class, function(MockInterface $mock) use($relayDomain, $domain) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $domain,
            ])->andReturn($relayDomain);
        });

        $this->postJson(route('api.v1.relay-domain.store'), [
            'domain' => $domain,
        ])->assertSuccessful();
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->be($user);

        $relayDomain = RelayDomain::factory()->create();

        $this->mock(DeleteRelayDomain::class, function(MockInterface $mock) use($relayDomain) {
            $mock->shouldReceive('execute')->once()->withArgs([
                \Mockery::on(function($arg) use($relayDomain) {
                    return $arg instanceof RelayDomain && $arg->getKey() === $relayDomain->getKey();
                })
            ]);
        });

        $this->deleteJson(route('api.v1.relay-domain.destroy', $relayDomain->getKey()))->assertSuccessful();
    }
}
