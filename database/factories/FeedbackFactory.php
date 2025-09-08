<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = fake()->numberBetween(1, 5);
        $comments = [
            1 => [
                'Very disappointing service.',
                'Not satisfied with the treatment.',
                'Would not recommend.',
                'Poor experience overall.'
            ],
            2 => [
                'Below expectations.',
                'Service could be better.',
                'Some issues with the treatment.',
                'Not entirely satisfied.'
            ],
            3 => [
                'Average service.',
                'It was okay.',
                'Nothing special but acceptable.',
                'Decent treatment.'
            ],
            4 => [
                'Good service overall.',
                'Happy with the treatment.',
                'Would consider coming back.',
                'Professional staff and good results.'
            ],
            5 => [
                'Excellent service!',
                'Amazing results, highly recommend!',
                'Perfect treatment, very professional staff.',
                'Outstanding experience, will definitely return!',
                'Best clinic in town, incredible results!'
            ]
        ];

        return [
            'client_id' => User::factory()->client(),
            'appointment_id' => Appointment::factory()->completed(),
            'rating' => $rating,
            'comment' => fake()->randomElement($comments[$rating]),
        ];
    }
}