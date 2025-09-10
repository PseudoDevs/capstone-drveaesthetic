<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\ClinicService;
use App\Models\Appointment;
use App\Models\Feedback;
use App\Models\MedicalCertificate;
use App\Models\TimeLogs;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting to seed the database...');

        // Create Admin User
        $this->command->info('👤 Creating admin user...');
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@clinic.com',
        ]);

        // Create Doctors
        $this->command->info('👩‍⚕️ Creating doctors...');
        $doctors = User::factory()->doctor()->count(1)->create();

        // Create Staff
        $this->command->info('👥 Creating staff members...');
        $staff = User::factory()->staff()->count(1)->create();

        // Create Clients
        $this->command->info('🤝 Creating clients...');
        $clients = User::factory()->client()->count(10)->create();

        // Create Categories
        $this->command->info('📂 Creating service categories...');
        $categories = collect([
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
        ])->map(function ($categoryName) {
            return Category::factory()->create(['category_name' => $categoryName]);
        });

        // Create Clinic Services
        $this->command->info('🏥 Creating clinic services...');
        $services = collect();
        $categories->each(function ($category) use (&$services, $doctors) {
            $categoryServices = ClinicService::factory()
                ->count(rand(2, 4))
                ->create([
                    'category_id' => $category->id,
                    'staff_id' => $doctors->random()->id,
                ]);
            $services = $services->merge($categoryServices);
        });

        // Create Appointments
        $this->command->info('📅 Creating appointments...');
        $appointments = collect();
        
        // Create various types of appointments
        $pendingAppointments = Appointment::factory()
            ->pending()
            ->count(15)
            ->create([
                'client_id' => fn() => $clients->random()->id,
                'service_id' => fn() => $services->random()->id,
                'staff_id' => fn() => $doctors->random()->id,
            ]);

        $scheduledAppointments = Appointment::factory()
            ->scheduled()
            ->count(25)
            ->create([
                'client_id' => fn() => $clients->random()->id,
                'service_id' => fn() => $services->random()->id,
                'staff_id' => fn() => $doctors->random()->id,
            ]);

        $completedAppointments = Appointment::factory()
            ->completed()
            ->count(40)
            ->create([
                'client_id' => fn() => $clients->random()->id,
                'service_id' => fn() => $services->random()->id,
                'staff_id' => fn() => $doctors->random()->id,
            ]);

        $cancelledAppointments = Appointment::factory()
            ->cancelled()
            ->count(10)
            ->create([
                'client_id' => fn() => $clients->random()->id,
                'service_id' => fn() => $services->random()->id,
                'staff_id' => fn() => $doctors->random()->id,
            ]);

        $appointments = $pendingAppointments
            ->merge($scheduledAppointments)
            ->merge($completedAppointments)
            ->merge($cancelledAppointments);

        // Create Feedback for completed appointments
        $this->command->info('⭐ Creating feedback...');
        $completedAppointments->each(function ($appointment) {
            if (rand(1, 100) <= 70) { // 70% chance of feedback
                Feedback::factory()->create([
                    'client_id' => $appointment->client_id,
                    'appointment_id' => $appointment->id,
                ]);
            }
        });

        // Create Medical Certificates
        $this->command->info('📋 Creating medical certificates...');
        MedicalCertificate::factory()->count(30)->create([
            'staff_id' => fn() => $doctors->random()->id,
            'client_id' => fn() => $clients->random()->id,
        ]);

        // Create Time Logs for staff and doctors
        $this->command->info('⏰ Creating time logs...');
        $workingUsers = $staff->merge($doctors);
        $workingUsers->each(function ($user) {
            // Create logs for the past 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                
                // 80% chance of having a log for each day
                if (rand(1, 100) <= 80) {
                    // Only create log for weekdays
                    if ($date->isWeekday()) {
                        TimeLogs::factory()->completed()->create([
                            'user_id' => $user->id,
                            'date' => $date->format('Y-m-d'),
                        ]);
                    }
                }
            }

            // Create an active log for today (if today is weekday)
            if (now()->isWeekday() && rand(1, 100) <= 30) { // 30% chance of being currently active
                TimeLogs::factory()->active()->create([
                    'user_id' => $user->id,
                    'date' => now()->format('Y-m-d'),
                ]);
            }
        });

        // Create Chats and Messages
        $this->command->info('💬 Creating chats and messages...');
        $chats = Chat::factory()->count(10)->create([
            'staff_id' => $staff->first()->id, // Use the single staff member for all chats
            'client_id' => fn() => $clients->random()->id,
        ]);

        $chats->each(function ($chat) {
            // Create 3-15 messages per chat
            $messageCount = rand(3, 15);
            
            for ($i = 0; $i < $messageCount; $i++) {
                // Alternate between staff and client messages
                $isFromStaff = $i % 2 === 0;
                $userId = $isFromStaff ? $chat->staff_id : $chat->client_id;
                
                Message::factory()->create([
                    'chat_id' => $chat->id,
                    'user_id' => $userId,
                    'is_read' => rand(1, 100) <= 80, // 80% chance of being read
                ]);
            }
            
            // Update chat's last_message_at to the latest message timestamp
            $latestMessage = $chat->messages()->latest()->first();
            if ($latestMessage) {
                $chat->update(['last_message_at' => $latestMessage->created_at]);
            }
        });

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('📊 Summary:');
        $this->command->line("   • Users: " . User::count() . " (1 admin, {$doctors->count()} doctors, {$staff->count()} staff, {$clients->count()} clients)");
        $this->command->line("   • Categories: " . Category::count());
        $this->command->line("   • Services: " . ClinicService::count());
        $this->command->line("   • Appointments: " . Appointment::count());
        $this->command->line("   • Feedback: " . Feedback::count());
        $this->command->line("   • Medical Certificates: " . MedicalCertificate::count());
        $this->command->line("   • Time Logs: " . TimeLogs::count());
        $this->command->line("   • Chats: " . Chat::count());
        $this->command->line("   • Messages: " . Message::count());
    }
}
