<?php

namespace Services\Rules\Actions;

use App\Services\Rules\Actions\ValidateClient;
use App\Services\Rules\Actions\ValidateSender;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ValidateSenderTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            [
                'mail_from_address',
                'mail@contoso.com',
                null,
            ],
            [
                'mail_from_address',
                'mail_contoso.com',
                ValidationException::class,
            ]
        ];

    }

    /**
     * @dataProvider dataProvider
     */
    public function test(string $senderType, string $senderPayload, ?string $exception)
    {
        $exception
            ? $this->expectException($exception)
            : $this->addToAssertionCount(1);


        $action = app(ValidateSender::class);

        $action->execute($senderType, $senderPayload, $exception);
    }
}
