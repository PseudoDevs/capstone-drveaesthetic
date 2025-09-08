<?php

namespace Database\Seeders;

use App\Models\ClinicService;
use Illuminate\Database\Seeder;

class UpdateClinicServicesDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Updating clinic services with descriptions...');

        $serviceDescriptions = [
            'Botox Injection' => 'Professional botox injections to reduce fine lines and wrinkles. Our experienced practitioners use premium products to deliver natural-looking results with minimal downtime.',
            'Dermal Filler' => 'Restore volume and enhance facial contours with our advanced dermal filler treatments. Safe, effective, and long-lasting results for a youthful appearance.',
            'Chemical Peel' => 'Rejuvenate your skin with our customized chemical peel treatments. Remove dead skin cells, reduce acne scars, and reveal smoother, brighter skin.',
            'Laser Hair Removal' => 'Say goodbye to unwanted hair with our state-of-the-art laser technology. Safe for all skin types with permanent results and minimal discomfort.',
            'Microdermabrasion' => 'Gentle exfoliation treatment that removes dead skin cells and stimulates collagen production. Perfect for improving skin texture and reducing pore size.',
            'Facial Treatment' => 'Luxurious facial treatments tailored to your skin type. Deep cleansing, hydration, and relaxation in one comprehensive session for radiant skin.',
            'Acne Treatment' => 'Comprehensive acne treatment program combining medical-grade products and advanced techniques to clear existing breakouts and prevent future ones.',
            'Skin Rejuvenation' => 'Advanced skin rejuvenation therapy using cutting-edge technology to improve skin tone, texture, and elasticity for a more youthful complexion.',
            'Anti-Aging Treatment' => 'Combat signs of aging with our multi-modal approach including peptides, antioxidants, and growth factors to restore skin vitality and firmness.',
            'Consultation' => 'Professional consultation with our expert dermatologists to assess your skin concerns and develop a personalized treatment plan.',
            'Hydrafacial' => 'Deep cleansing, exfoliation, and hydration treatment that removes impurities while delivering nourishing serums for instantly radiant skin.',
            'IPL Photofacial' => 'Intense pulsed light therapy to reduce sun damage, age spots, and improve overall skin tone with minimal downtime.',
            'Radiofrequency Skin Tightening' => 'Non-invasive treatment that stimulates collagen production to tighten and firm loose skin areas for a more youthful appearance.',
            'PRP Therapy' => 'Platelet-rich plasma therapy that uses your own blood components to stimulate natural healing and rejuvenation processes.',
            'Cryotherapy' => 'Advanced cooling therapy to reduce inflammation, tighten pores, and enhance skin texture through controlled temperature treatments.',
        ];

        $updatedCount = 0;

        foreach ($serviceDescriptions as $serviceName => $description) {
            $services = ClinicService::where('service_name', 'LIKE', "%{$serviceName}%")->get();
            
            foreach ($services as $service) {
                if (empty($service->description)) {
                    $service->update(['description' => $description]);
                    $updatedCount++;
                    $this->command->line("   ✓ Updated: {$service->service_name}");
                }
            }
        }

        // For any remaining services without descriptions, add generic ones
        $servicesWithoutDescription = ClinicService::whereNull('description')
            ->orWhere('description', '')
            ->get();

        foreach ($servicesWithoutDescription as $service) {
            $genericDescription = "Professional {$service->service_name} service provided by our experienced medical team. Tailored treatment approach to meet your individual needs and deliver optimal results.";
            
            $service->update(['description' => $genericDescription]);
            $updatedCount++;
            $this->command->line("   ✓ Added generic description: {$service->service_name}");
        }

        $this->command->info("✅ Successfully updated {$updatedCount} clinic service descriptions!");
    }
}