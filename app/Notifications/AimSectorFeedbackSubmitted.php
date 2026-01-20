<?php

namespace App\Notifications;

use App\Models\AimSectorFeedback;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AimSectorFeedbackSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected AimSectorFeedback $feedback;
    protected User $actor;

    /**
     * Create a new notification instance.
     */
    public function __construct(AimSectorFeedback $feedback, User $actor)
    {
        $this->feedback = $feedback;
        $this->actor = $actor;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'aim_sector_feedback_submitted',
            'action' => 'submitted',
            'message' => 'تم إرسال تغذية راجعة جديدة للاعتماد.',
            'feedback_id' => $this->feedback->id,
            'aim_title' => $this->feedback->aim->title ?? 'مؤشر', // Assuming relation exists
            'sector_name' => $this->feedback->sector->name ?? 'قطاع', // Assuming relation exists
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'url' => route('aim_sector_feedback.show', $this->feedback),
        ];
    }
}
