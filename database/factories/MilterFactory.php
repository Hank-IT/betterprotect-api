<?php

namespace Database\Factories;

use App\Services\Milter\Models\Milter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Recipients\Models\RelayRecipient>
 */
class MilterFactory extends Factory
{
    protected $model = Milter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'definition' => 'inet:localhost:8892',
            'description' => fake()->text,
        ];
    }
}
