<?php

namespace Database\Factories;

use App\Services\Server\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ServerFactory extends Factory
{
    protected $model = Server::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hostname' => fake()->unique()->domainName,

            'postfix_db_host' => fake()->domainName,
            'postfix_db_name' => fake()->name,
            'postfix_db_user' => fake()->userName,
            'postfix_db_port' => fake()->numberBetween(1, 65535),
            'postfix_db_password' => encrypt(fake()->password),

            'log_db_host' => fake()->domainName,
            'log_db_name' => fake()->name,
            'log_db_user' => fake()->userName,
            'log_db_port' => fake()->numberBetween(1, 65535),
            'log_db_password' => encrypt(fake()->password),

            'ssh_user' => fake()->userName,
            'ssh_public_key' => fake()->text,
            'ssh_private_key' => encrypt(fake()->text),
            'ssh_command_sudo' => fake()->filePath(),
            'ssh_command_postqueue' => fake()->filePath(),
            'ssh_command_postsuper' => fake()->filePath(),
        ];
    }
}
