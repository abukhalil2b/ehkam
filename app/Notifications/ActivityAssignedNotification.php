<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Activity;

class ActivityAssignedNotification extends Notification
{
    use Queueable;

    public $activity;

    /**
     * Create a new notification instance.
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'activity_title' => $this->activity->title,
            'message' => 'تم تعيينك لتقييم نشاط: ' . $this->activity->title,
            'url' => route('activity.show', $this->activity->id),
        ];
    }
}
