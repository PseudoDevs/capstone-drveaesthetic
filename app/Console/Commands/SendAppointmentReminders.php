<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send appointment reminders to clients';

    public function handle()
    {
        $this->info('Starting appointment reminder process...');

        // Send 24-hour reminders
        $this->send24HourReminders();
        
        // Send 2-hour reminders
        $this->send2HourReminders();

        $this->info('Appointment reminder process completed.');
    }

    private function send24HourReminders()
    {
        $tomorrow = Carbon::tomorrow();
        
        $appointments = Appointment::with(['client', 'service', 'staff'])
            ->where('appointment_date', $tomorrow)
            ->whereIn('status', ['pending', 'scheduled'])
            ->whereDoesntHave('notifications', function($query) {
                $query->where('type', 'appointment_reminder')
                      ->where('data->reminder_type', '24h');
            })
            ->get();

        $this->info("Found {$appointments->count()} appointments for 24-hour reminders");

        foreach ($appointments as $appointment) {
            // Check if user has 24h reminders enabled
            $preferences = $appointment->client->notificationPreferences;
            if ($preferences && $preferences->appointment_reminders_24h) {
                $appointment->client->notify(new AppointmentReminderNotification($appointment, '24h'));
                $this->line("Sent 24h reminder to: {$appointment->client->name}");
            }
        }
    }

    private function send2HourReminders()
    {
        $twoHoursFromNow = Carbon::now()->addHours(2);
        $today = Carbon::today();
        
        $appointments = Appointment::with(['client', 'service', 'staff'])
            ->where('appointment_date', $today)
            ->where('appointment_time', '>=', $twoHoursFromNow->format('H:i'))
            ->where('appointment_time', '<=', $twoHoursFromNow->addMinutes(30)->format('H:i'))
            ->whereIn('status', ['pending', 'scheduled'])
            ->whereDoesntHave('notifications', function($query) {
                $query->where('type', 'appointment_reminder')
                      ->where('data->reminder_type', '2h');
            })
            ->get();

        $this->info("Found {$appointments->count()} appointments for 2-hour reminders");

        foreach ($appointments as $appointment) {
            // Check if user has 2h reminders enabled
            $preferences = $appointment->client->notificationPreferences;
            if ($preferences && $preferences->appointment_reminders_2h) {
                $appointment->client->notify(new AppointmentReminderNotification($appointment, '2h'));
                $this->line("Sent 2h reminder to: {$appointment->client->name}");
            }
        }
    }
}
