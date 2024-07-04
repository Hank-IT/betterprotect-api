<?php

namespace Tests\Feature\Services\Authentication\Commands;

use App\Services\Authentication\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function test()
    {
        $this->artisan('user:create')
            ->expectsQuestion('What is the username?', 'test')
            ->expectsQuestion('What is the password?', 'password')
            ->expectsOutput('User test was created successfully.')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'username' => fake()->userName(),
            'role' => 'administrator',
        ]);

        $model = User::query()->where('username', '=', 'test')->first();

        $this->assertTrue(Hash::check('password', $model->password));
    }
}
