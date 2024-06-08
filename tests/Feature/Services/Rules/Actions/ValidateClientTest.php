<?php

namespace Tests\Feature\Services\Rules\Actions;

use App\Services\Rules\Actions\ValidateClient;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ValidateClientTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            [
                'client_ipv4',
                '127.0.0.1',
                null,
            ],
            [
                'client_ipv4',
                '127.0.0.0/24',
                ValidationException::class,
            ],
            [
                'client_ipv6',
                '2001:0000:130F:0000:0000:09C0:876A:130B',
                null,
            ],
            [
                'client_ipv6',
                '127.0.0.1',
                ValidationException::class,
            ],
            [
                'client_ipv4_net',
                '127.0.0.0/24',
                null,
            ],
            [
                'client_ipv4_net',
                '127.0.0.0/33',
                ValidationException::class,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(string $clientType, string $clientPayload, ?string $exception)
    {
        $exception
            ? $this->expectException($exception)
            : $this->addToAssertionCount(1);

        $action = app(ValidateClient::class);

        $action->execute($clientType, $clientPayload, $exception);
    }
}
