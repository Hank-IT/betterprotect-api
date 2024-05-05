<?php

namespace Services\Milter\Actions;

use App\Services\Milter\Actions\ValidateMilterExceptionClient;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ValidateMilterExceptionClientTest extends TestCase
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
                '127.0.0.256',
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
                '192.168.0.0/24',
                null,
            ],
            [
                'client_ipv4_net',
                '10.0.0.0/8',
                ValidationException::class,
            ],
            [
                'client_ipv4_net',
                '192.168.0.1',
                ValidationException::class,
            ]
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

        app(ValidateMilterExceptionClient::class)->execute($clientType, $clientPayload);
    }
}
