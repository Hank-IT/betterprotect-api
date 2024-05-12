<?php

namespace Tests\Feature\Services\Recipients\Actions;

use App\Services\Recipients\Actions\SanitizeLdapEmail;
use Tests\TestCase;

class SanitizeLdapEmailTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            [
                'SPO:SPO_bb0102a2-270b-40f1-bc63-538e345b568a@SPO_e7997257-b6f5-461a-8fca-c5607426d47b',
                [],
                null,
            ],
            [
                'smtp:demo@contoso.onmicrosoft.com',
                [],
                'demo@contoso.onmicrosoft.com'
            ],
            [
                'smtp:demo@contoso.onmicrosoft.com',
                ['contoso.onmicrosoft.com'],
                null,
            ],
            [
                'SMTP:ceo@contoso.com',
                [],
                'ceo@contoso.com',
            ],
            [
                'x500:/o=First Organization/ou=Exchange Administrative Group (c34b6412-63c3-42fe-b4ad-4a94cb4515c8)/cn=Recipients/cn=da43fc18-e347-46c4-946c-a26ceb34768e',
                [],
                null,
            ],
            [
                'SMTP:ceo@contoso.com',
                ['contoso.onmicrosoft.com'],
                'ceo@contoso.com',
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(string $email, array $ignoredDomains, ?string $expected)
    {
        $this->assertEquals(
            $expected,
            app(SanitizeLdapEmail::class)->execute($email, $ignoredDomains),
        );
    }
}
