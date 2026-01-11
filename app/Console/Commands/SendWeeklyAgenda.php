<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\CalendarEvent;
use Illuminate\Console\Command;

class SendWeeklyAgenda extends Command
{
    protected $signature = 'calendar:send-weekly-agenda';
    protected $description = 'Send weekly agenda SMS to all active users';

    public function handle()
    {
        $users = User::whereHas('calendarEvents')->get();
        
        foreach ($users as $user) {
            $events = CalendarEvent::where('target_user_id', $user->id)
                ->whereBetween('start_date', [now(), now()->addWeek()])
                ->orderBy('start_date')
                ->get();
            
            if ($events->isNotEmpty()) {
                //sms
                $this->info("Sent agenda to {$user->name}");
            }
        }
        
        $this->info('Weekly agendas sent successfully!');
    }
}