<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'staff_id' => User::factory()->state(['role' => fake()->randomElement(['Staff', 'Doctor'])]),
            'client_id' => User::factory()->client(),
            'last_message_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ];
    }
}