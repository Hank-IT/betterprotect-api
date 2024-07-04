<?php

namespace Tests\Feature\Services\Recipients\Actions;

use App\Services\Recipients\Actions\PullRecipientsFromLdap;
use Mockery;
use App\Services\Recipients\Actions\ProcessLdapEntityAsRecipient;
use Illuminate\Support\Str;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Models\ActiveDirectory\User;
use Mockery\MockInterface;
use Tests\TestCase;

class PullRecipientsFromLdapTest extends TestCase
{
    public function test()
    {
        DirectoryEmulator::setup('default');

        $ignoredDomains = [(string) Str::uuid()];

        $user = User::create([
            'id' => (string) Str::uuid(),
            'proxyAddresses' => $userEmails = [
                fake()->email,
                fake()->email,
            ]
        ]);

        $user2 = User::create([
            'id' => (string) Str::uuid(),
            'proxyAddresses' => []
        ]);

        $group = Group::create([
            'id' => (string) Str::uuid(),
            'proxyAddresses' => $groupEmails = [
                 fake()->email,
                 fake()->email,
            ]
        ]);

        $this->mock(ProcessLdapEntityAsRecipient::class, function(MockInterface $mock) use($user, $userEmails, $group, $groupEmails, $user2, $ignoredDomains) {
            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($user) {
                    return $arg->id === $user->id;
                }),
                $ignoredDomains,
            ])->andReturn($userEmails);

            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($user2) {
                    return $arg->id === $user2->id;
                }),
                $ignoredDomains,
            ])->andReturn([]);

            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($group) {
                    return $arg->id === $group->id;
                }),
                $ignoredDomains,
            ])->andReturn($groupEmails);
        });

        $this->assertEquals(
            array_merge($userEmails, $groupEmails),
            app(PullRecipientsFromLdap::class)->execute($ignoredDomains)
        );
    }
}
