<?php

namespace Tests\Feature\Services\Authentication;

use App\Exceptions\ErrorException;
use Exception;
use App\Services\Authentication\Authorizer;
use App\Services\Authentication\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;

class AuthorizerTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            // readonly
            [
                'readonly',
                'readonly',
                true,
            ],
            [
                'readonly',
                'authorizer',
                true,
            ],
            [
                'readonly',
                'editor',
                true,
            ],
            [
                'readonly',
                'administrator',
                true,
            ],

            // authorizer
            [
                'authorizer',
                'readonly',
                false,
            ],
            [
                'authorizer',
                'authorizer',
                true,
            ],
            [
                'authorizer',
                'editor',
                true,
            ],
            [
                'authorizer',
                'administrator',
                true,
            ],

            // editor
            [
                'editor',
                'readonly',
                false,
            ],
            [
                'editor',
                'authorizer',
                false,
            ],
            [
                'editor',
                'editor',
                true,
            ],
            [
                'editor',
                'administrator',
                true,
            ],

            // administrator
            [
                'administrator',
                'readonly',
                false,
            ],
            [
                'administrator',
                'authorizer',
                false,
            ],
            [
                'administrator',
                'editor',
                false,
            ],
            [
                'administrator',
                'administrator',
                true,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(string $testRole, string $userRole, bool $state)
    {
        $user = User::factory()->create([
            'role' => $userRole,
        ]);

        $authorizer = new Authorizer($user);

        $this->assertEquals($state, $authorizer->isRole($testRole));
    }

    public function test_exception_non_existing_user_role()
    {
        $this->expectException(ErrorException::class);

        $user = User::factory()->create([
            'role' => 'non-existing',
        ]);

        $authorizer = new Authorizer($user);

        $this->assertFalse($authorizer->isRole('non-existing'));
    }

    public function test_exception_non_existing_test_role()
    {
        $this->expectException(ErrorException::class);

        $user = User::factory()->create([
            'role' => 'administrator',
        ]);

        $authorizer = new Authorizer($user);

        $this->assertFalse($authorizer->isRole('non-existing'));
    }

    public function test_exception_non_existing_usere()
    {
        $this->expectException(ErrorException::class);

        $user = User::factory()->make([
            'role' => 'administrator',
        ]);

        $authorizer = new Authorizer($user);

        $this->assertFalse($authorizer->isRole('administrator'));
    }
}
