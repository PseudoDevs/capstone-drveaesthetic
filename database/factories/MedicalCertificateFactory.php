<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalCertificate>
 */
class MedicalCertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'staff_id' => User::factory()->doctor(),
            'client_id' => User::factory()->client(),
            'purpose' => fake()->randomElement([
                'Sick Leave',
                'Medical Excuse',
                'Fitness Certificate',
                'Treatment Verification',
                'Recovery Period',
                'Work Accommodation',
                'Insurance Claim',
                'Travel Medical Clearance'
            ]),
            'amount' => fake()->randomFloat(2, 500, 2000),
            'is_issued' => fake()->boolean(70),
        ];
    }

    public function issued(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_issued' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_issued' => false,
        ]);
    }
}