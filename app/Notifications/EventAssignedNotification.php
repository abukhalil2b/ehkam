<?php

namespace App\Notifications;

use App\Models\CalendarEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels; // Important for Queued Models

class EventAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $event;

    public function __construct(CalendarEvent $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * 1. Email Channel
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📅 نشاط جديد: ' . $this->event->title)
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('قام **' . $this->event->user->name . '** بإضافة نشاط جديد إلى جدولك.')
            ->line('---')
            ->line('**📌 العنوان:** ' . $this->event->title)
            ->line('**🗓️ التاريخ:** ' . $this->event->start_date->format('Y-m-d H:i') . ' (' . ($this->event->hijri_date ?? '') . ')')
            ->line('**⏳ المدة:** ' . $this->event->duration_human)
            ->line('**📝 التصنيف:** ' . __($this->event->type))
            ->action('عرض في التقويم', route('calendar.index', ['year' => $this->event->year]))
            ->line('شكراً لاستخدامك نظام التقويم الإلكتروني.');
    }

    /**
     * 2. Database Channel (In-App Notification)
     * Stores in 'notifications' table, 'data' column
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'calendar_event', // To choose icon in frontend
            'event_id' => $this->event->id,
            'title' => 'نشاط جديد: ' . $this->event->title,
            'message' => 'أضاف ' . $this->event->user->name . ' نشاطاً لتقويمك',
            'start_date' => $this->event->start_date->diffForHumans(),
            'bg_color' => $this->event->bg_color, // Use event color for the notification dot
            'link' => route('calendar.index', ['year' => $this->event->year]),
            'icon' => 'calendar-plus', // For frontend SVG logic
        ];
    }

    /**
     * 3. Future SMS Channel (Placeholder)
     * When ready, install a driver (e.g., Vonage or Twilio) and uncomment.
     */
    /*
    public function toVonage($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\VonageMessage)
            ->content('تم إضافة نشاط جديد: ' . $this->event->title . ' في ' . $this->event->start_date->format('m-d H:i'));
    }
    */

    // OR for a generic SMS driver
    /*
    public function toSms($notifiable)
    {
        return [
            'to' => $notifiable->phone_number,
            'message' => 'تم إضافة نشاط جديد: ' . $this->event->title
        ];
    }
    */
}
