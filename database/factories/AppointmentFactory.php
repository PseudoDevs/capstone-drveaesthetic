<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ClinicService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => User::factory()->client(),
            'service_id' => ClinicService::factory(),
            'staff_id' => User::factory()->doctor(),
            'appointment_date' => fake()->dateTimeBetween('now', '+3 months'),
            'appointment_time' => fake()->time(),
            'status' => fake()->randomElement(['PENDING', 'SCHEDULED', 'COMPLETED', 'CANCELLED']),
            'is_paid' => fake()->boolean(60),
            'is_rescheduled' => fake()->boolean(15),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'PENDING',
            'is_paid' => false,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'SCHEDULED',
            'is_paid' => fake()->boolean(80),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'COMPLETED',
            'is_paid' => true,
            'appointment_date' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'CANCELLED',
            'is_paid' => fake()->boolean(20),
        ]);
    }
}