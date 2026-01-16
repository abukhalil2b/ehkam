<?php

namespace App\Notifications;

use App\Models\Step;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkflowStepNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Step $step;
    protected string $action;
    protected string $message;
    protected User $actor;

    /**
     * Create a new notification instance.
     */
    public function __construct(Step $step, string $action, string $message, User $actor)
    {
        $this->step = $step;
        $this->action = $action;
        $this->message = $message;
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
            'type' => 'workflow_step',
            'action' => $this->action,
            'message' => $this->message,
            'step_id' => $this->step->id,
            'step_name' => $this->step->name,
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'current_stage' => $this->step->currentStage?->name,
            'workflow' => $this->step->workflow?->name,
        ];
    }

    /**
     * Get the mail representation of the notification (optional).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تحديث سير العمل: ' . $this->step->name)
            ->line($this->message)
            ->line('الخطوة: ' . $this->step->name)
            ->line('بواسطة: ' . $this->actor->name)
            ->action('عرض الخطوة', url('/steps/' . $this->step->id));
    }
}
