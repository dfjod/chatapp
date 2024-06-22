<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_from' => \App\Models\User::all()->random()->id,
            'chat_to' => \App\Models\Chat::all()->random()->id,
            'content' => fake()->sentence(),
        ];
    }
}
