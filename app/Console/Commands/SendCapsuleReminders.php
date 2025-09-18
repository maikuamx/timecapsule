<?php

namespace App\Console\Commands;

use App\Models\TimeCapsule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCapsuleReminders extends Command
{
    protected $signature = 'capsules:send-reminders';
    protected $description = 'Send email reminders for time capsules that will unlock within 7 days';

    public function handle()
    {
        $capsules = TimeCapsule::needingReminder() -> with('user') -> get();

        $this->into("Found {$capsules->count()} time capsules that need reminders");

        foreach ($capsules as $capsule) {
            try{
                $this->sendReminderEmail($capsule);

                $capsule->update(['reminder_sent' => true]);

                $this->info("reminder sent for {$capsule->title}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for {$capsule->id}: {$e->getMessage()}");
            }
        }

        return  Command::SUCCESS;
    }

    private function sendReminderEmail(TimeCapsule $capsule)
    {
        //TODO Implement email logic
    }
}
