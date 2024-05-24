<?php

namespace Tests\Feature\Controllers\API;

use App\Services\Authentication\Models\User;
use App\Services\User\Actions\CreateUser;
use Mockery\MockInterface;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_index()
    {
        $user = User::factory()->create();

        $this->be($user);

        User::factory()->count(5)->create();

        $response = $this->getJson(route('api.v1.user.index', [
            'page_number' => 1,
            'page_size' => 2,
        ]))->assertSuccessful();

        $this->assertCount(2, $response['data']);
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $this->be($user);

        $data = User::factory()->make();
        $password = fake()->password;

        $this->mock(CreateUser::class, function(MockInterface $mock) use($data, $password) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $data['username'], $password, $data['role'], $data['email'],
            ])->andReturn($data);
        });

        $this->postJson(route('api.v1.user.store'), [
            'username' => $data->username,
            'password' => $password,
            'password_confirmation' => $password,
            'role' => $data->role,
            'email' => $data->email,
        ])->assertSuccessful();
    }

    public function test_show()
    {
        $user = User::factory()->create();

        $this->be($user);

        $response = $this->getJson(route('api.v1.user.show', $user->getKey()))->assertSuccessful();

        $this->assertEquals($user->getKey(), $response['data']['id']);
    }

    public function test_update()
    {
        $user = User::factory()->create();

        $this->be($user);

        $updatableUser = User::factory()->create();

        $targetEmail = fake()->email;
        $targetRole = 'readonly';

        $this->assertNotEquals($targetEmail, $updatableUser->email);
        $this->assertNotEquals($targetRole, $updatableUser->role);

        $this->patchJson(route('api.v1.user.update', $updatableUser->getKey()), [
            'email' => $targetEmail,
            'role' => $targetRole,
        ])->assertSuccessful();

        $updatableUser->refresh();

        $this->assertEquals($targetEmail, $updatableUser->email);
        $this->assertEquals($targetRole, $updatableUser->role);
    }

    public function test_delete_auth_user()
    {
        $user = User::factory()->create();

        $this->be($user);

        $this->deleteJson(route('api.v1.user.destroy', $user->getKey()))->assertStatus(422);
    }

    public function test_delete()
    {
        $user = User::factory()->create();

        $this->be($user);

        $deletableUser = User::factory()->create();

        $this->deleteJson(route('api.v1.user.destroy', $deletableUser->getKey()))->assertSuccessful();

        $this->assertModelMissing($deletableUser);
    }
}
