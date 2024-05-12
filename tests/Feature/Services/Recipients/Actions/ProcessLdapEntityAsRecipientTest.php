<?php

namespace Tests\Feature\Services\Recipients\Actions;

use App\Services\Recipients\Actions\ProcessLdapEntityAsRecipient;
use App\Services\Recipients\Actions\SanitizeLdapEmail;
use Closure;
use Illuminate\Support\Str;
use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessLdapEntityAsRecipientTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            [
                ['mail@contoso.com' => (string)Str::uuid()],
                [
                    (string)Str::uuid(),
                ],
                function (array $emails) {
                    return new User([
                        'proxyAddresses' => $emails
                    ]);
                },
                ['mail@contoso.com'],
            ],
            [
                ['team@contoso.com' => (string)Str::uuid()],
                [
                    (string)Str::uuid(),
                ],
                function (array $emails) {
                    return new Group([
                        'proxyAddresses' => $emails
                    ]);
                },
                ['team@contoso.com'],
            ],
            [
                [
                    null => (string)Str::uuid()
                ],
                [
                    (string)Str::uuid(),
                ],
                function (array $emails) {
                    return new User([
                        'proxyAddresses' => $emails
                    ]);
                },
                [],
            ],
            [
                [
                    'team@contoso.com' => (string)Str::uuid(),
                    'other-team@contoso.com' => (string)Str::uuid(),
                ],
                [
                    (string)Str::uuid(),
                ],
                function (array $emails) {
                    return new Group([
                        'proxyAddresses' => $emails
                    ]);
                },
                ['team@contoso.com', 'other-team@contoso.com'],
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(array $emails, array $ignoredDomains, Closure $entity, ?array $return)
    {
        $entity = $entity($emails);

        $this->mock(SanitizeLdapEmail::class, function (MockInterface $mock) use ($emails, $ignoredDomains) {
            foreach($emails as $return => $value) {
                $mock->shouldReceive('execute')->once()->withArgs([
                    $value,
                    $ignoredDomains,
                ])->andReturn($return);
            }
        });

        $this->assertEquals(
            $return, app(ProcessLdapEntityAsRecipient::class)->execute($entity, $ignoredDomains)
        );
    }
}
