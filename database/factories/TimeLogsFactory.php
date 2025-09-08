<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLogs>
 */
class TimeLogsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 month', 'now');
        $clockIn = fake()->dateTimeBetween($date->format('Y-m-d') . ' 07:00:00', $date->format('Y-m-d') . ' 09:00:00');
        $clockOut = fake()->dateTimeBetween($clockIn, $date->format('Y-m-d') . ' 18:00:00');
        
        $clockInCarbon = Carbon::parse($clockIn);
        $clockOutCarbon = Carbon::parse($clockOut);
        $totalHours = $clockOutCarbon->diffInMinutes($clockInCarbon) / 60;

        return [
            'user_id' => User::factory()->state(['role' => fake()->randomElement(['Staff', 'Doctor'])]),
            'clock_in' => $clockIn,
            'clock_out' => fake()->boolean(90) ? $clockOut : null,
            'total_hours' => fake()->boolean(90) ? round($totalHours, 2) : null,
            'date' => $date->format('Y-m-d'),
        ];
    }

    public function active(): static
    {
        return $this->state(function (array $attributes) {
            $date = now();
            $clockIn = fake()->dateTimeBetween($date->format('Y-m-d') . ' 07:00:00', $date->format('Y-m-d') . ' 09:00:00');
            
            return [
                'clock_in' => $clockIn,
                'clock_out' => null,
                'total_hours' => null,
                'date' => $date->format('Y-m-d'),
            ];
        });
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $date = fake()->dateTimeBetween('-1 month', '-1 day');
            $clockIn = fake()->dateTimeBetween($date->format('Y-m-d') . ' 07:00:00', $date->format('Y-m-d') . ' 09:00:00');
            $clockOut = fake()->dateTimeBetween($clockIn, $date->format('Y-m-d') . ' 18:00:00');
            
            $clockInCarbon = Carbon::parse($clockIn);
            $clockOutCarbon = Carbon::parse($clockOut);
            $totalHours = $clockOutCarbon->diffInMinutes($clockInCarbon) / 60;

            return [
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'total_hours' => round($totalHours, 2),
                'date' => $date->format('Y-m-d'),
            ];
        });
    }
}