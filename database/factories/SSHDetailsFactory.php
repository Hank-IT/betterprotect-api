<?php

namespace Database\Factories;

use App\Services\Server\dtos\SSHDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class SSHDetailsFactory extends Factory
{
    protected $model = SSHDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hostname' => fake()->domainName,
            'user' => fake()->userName,
            'public_key' => fake()->text,
            'private_Key' => '-----BEGIN RSA PRIVATE KEY-----
MIIBOwIBAAJBAJEbVaSOpiUGsq4CYjdDu7eKPvmM79/b6NEB4YNDrNsz84xE/acq
mr8iO1JzraCs4TMxNJ5Q6z3lIZOR2zA5cYcCAwEAAQJAdo/LDkWv4xjx7dPsxBMH
0hOXGPfbFyHrvLq4tDQbjI5HPgJ0KxtVGbx+iTvt4XjvWhc/OZh3nsNoDrXO9io2
YQIhAPFcV3I/5wvLJ1MYfK5/Hkx0Ey4+wK5sC+kRGMn1BzbrAiEAmehwGy6V65lZ
M5T/byDCa20kv/o2fMVT3MQwmib6QNUCIQDoQq472VajOLn88sF4wgcMF18lz2ln
771+aN9r6QkqRQIgZ5HZTxFsZd0N//42XkwHSU9rOZ1haVlI8/k6U6IDKfkCIQCk
2d4EQKJCvlS/rtd4jfUymEFb5zxYh6nY07Rb851u8Q==
-----END RSA PRIVATE KEY-----
',
            'sudo_command' => fake()->filePath(),
            'postqueue_command' => fake()->filePath(),
            'postsuper_command' => fake()->filePath(),
        ];
    }
}
