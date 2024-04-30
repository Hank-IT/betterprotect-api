<?php

namespace Tests\Feature\Controllers\API;

use App\Services\Authentication\Models\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * @test
     */
    public function loginTest()
    {
        $user = User::factory()->create();

        $this->postJson(route('api.v1.auth.login'), [
            'username' => $user->username,
            'password' => 'password',
        ])->assertSuccessful();
    }

    /**
     * @test
     */
    public function logoutTest()
    {
        $user = User::factory()->create();

        $this->be($user);

        $this->postJson(route('api.v1.auth.logout'))
            ->assertSuccessful();
    }
}
