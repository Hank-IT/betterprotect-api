<?php

namespace Tests\Feature\Services\Helpers\Actions;

use App\Services\Helpers\Actions\ProcessPassword;
use Tests\TestCase;

class ProcessPasswordTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            [
                '',
                'not-used',
                ''
            ],
            [
                null,
                'used',
                'used'
            ],
            [
                'password',
                'not-used',
                'password'
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(?string $password, ?string $default, ?string $expected)
    {
        $this->assertEquals($expected,  app(ProcessPassword::class)->execute($password, $default));
    }
}
