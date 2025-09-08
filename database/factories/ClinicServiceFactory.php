<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClinicService>
 */
class ClinicServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            'Botox Injection' => [
                'duration' => 30, 
                'price' => 5000,
                'description' => 'Professional botox injections to reduce fine lines and wrinkles. Our experienced practitioners use premium products to deliver natural-looking results with minimal downtime.'
            ],
            'Dermal Filler' => [
                'duration' => 45, 
                'price' => 8000,
                'description' => 'Restore volume and enhance facial contours with our advanced dermal filler treatments. Safe, effective, and long-lasting results for a youthful appearance.'
            ],
            'Chemical Peel' => [
                'duration' => 60, 
                'price' => 3000,
                'description' => 'Rejuvenate your skin with our customized chemical peel treatments. Remove dead skin cells, reduce acne scars, and reveal smoother, brighter skin.'
            ],
            'Laser Hair Removal' => [
                'duration' => 30, 
                'price' => 2500,
                'description' => 'Say goodbye to unwanted hair with our state-of-the-art laser technology. Safe for all skin types with permanent results and minimal discomfort.'
            ],
            'Microdermabrasion' => [
                'duration' => 45, 
                'price' => 2000,
                'description' => 'Gentle exfoliation treatment that removes dead skin cells and stimulates collagen production. Perfect for improving skin texture and reducing pore size.'
            ],
            'Facial Treatment' => [
                'duration' => 90, 
                'price' => 1500,
                'description' => 'Luxurious facial treatments tailored to your skin type. Deep cleansing, hydration, and relaxation in one comprehensive session for radiant skin.'
            ],
            'Acne Treatment' => [
                'duration' => 60, 
                'price' => 2500,
                'description' => 'Comprehensive acne treatment program combining medical-grade products and advanced techniques to clear existing breakouts and prevent future ones.'
            ],
            'Skin Rejuvenation' => [
                'duration' => 75, 
                'price' => 4000,
                'description' => 'Advanced skin rejuvenation therapy using cutting-edge technology to improve skin tone, texture, and elasticity for a more youthful complexion.'
            ],
            'Anti-Aging Treatment' => [
                'duration' => 60, 
                'price' => 3500,
                'description' => 'Combat signs of aging with our multi-modal approach including peptides, antioxidants, and growth factors to restore skin vitality and firmness.'
            ],
            'Consultation' => [
                'duration' => 30, 
                'price' => 500,
                'description' => 'Professional consultation with our expert dermatologists to assess your skin concerns and develop a personalized treatment plan.'
            ],
        ];

        $serviceName = fake()->randomElement(array_keys($services));
        $service = $services[$serviceName];

        return [
            'category_id' => Category::factory(),
            'staff_id' => User::factory()->doctor(),
            'service_name' => $serviceName,
            'description' => $service['description'],
            'duration' => $service['duration'],
            'price' => $service['price'],
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}