<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
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
        $messages = [
            'Hello, I would like to schedule an appointment.',
            'Can you provide more information about the treatment?',
            'What are your available time slots?',
            'I need to reschedule my appointment.',
            'Thank you for the consultation.',
            'Is there any preparation needed before the treatment?',
            'How long will the recovery take?',
            'Can I get a copy of my medical records?',
            'What are the payment options available?',
            'I have some questions about the procedure.',
            'When is my next appointment?',
            'Can you send me the treatment details?',
            'I\'m experiencing some side effects.',
            'The treatment results are amazing!',
            'How often should I come for follow-ups?'
        ];

        return [
            'chat_id' => Chat::factory(),
            'user_id' => User::factory(),
            'message' => fake()->randomElement($messages),
            'is_read' => fake()->boolean(70),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => false,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }
}