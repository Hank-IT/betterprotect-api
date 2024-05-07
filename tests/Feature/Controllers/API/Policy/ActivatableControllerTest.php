<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Milter\Models\MilterException;
use App\Services\Recipients\Models\RelayRecipient;
use App\Services\RelayDomains\Models\RelayDomain;
use App\Services\Rules\Models\ClientSenderAccess;
use App\Services\Transport\Models\Transport;
use Closure;
use Tests\TestCase;

class ActivatableControllerTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            [
                function () {
                    return ClientSenderAccess::factory()->create(['active' => false]);
                },
                'client-sender-access',
                true,
            ],
            [
                function () {
                    return ClientSenderAccess::factory()->create(['active' => true]);
                },
                'client-sender-access',
                false,
            ],
            [
                function () {
                    return MilterException::factory()->create(['active' => false]);
                },
                'milter-exception',
                true,
            ],
            [
                function () {
                    return MilterException::factory()->create(['active' => true]);
                },
                'milter-exception',
                false,
            ],
            [
                function () {
                    return RelayDomain::factory()->create(['active' => false]);
                },
                'relay-domain',
                true,
            ],
            [
                function () {
                    return RelayDomain::factory()->create(['active' => true]);
                },
                'relay-domain',
                false,
            ],
            [
                function () {
                    return RelayRecipient::factory()->create(['active' => false]);
                },
                'relay-recipient',
                true,
            ],
            [
                function () {
                    return RelayRecipient::factory()->create(['active' => true]);
                },
                'relay-recipient',
                false,
            ],
            [
                function () {
                    return Transport::factory()->create(['active' => false]);
                },
                'transport',
                true,
            ],
            [
                function () {
                    return Transport::factory()->create(['active' => true]);
                },
                'transport',
                false,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(Closure $callback, string $modelString, bool $state)
    {
        $user = User::factory()->create();

        $this->be($user);

        $model = $callback();

        $this->assertEquals(! $state, $model->isActive());

        $this->patchJson(route('api.v1.activation.update', [
            'activatableEntitiesEnum' => $modelString,
            'id' => $model->getKey(),
        ]), [
            'state' => $state,
        ])->assertSuccessful();

        $model->refresh();

        $this->assertEquals($state, $model->isActive());
    }
}
