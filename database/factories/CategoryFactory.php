<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_name' => fake()->randomElement([
                'Dermatology',
                'Cosmetic Surgery',
                'Laser Treatment',
                'Skin Care',
                'Anti-Aging',
                'Hair Treatment',
                'Body Contouring',
                'Facial Treatment',
                'Wellness',
                'Consultation'
            ]),
        ];
    }
}