<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StepWorkflowAssigned extends Notification
{
    use Queueable;

    public $step;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($step, $message = null)
    {
        $this->step = $step;
        $this->message = $message ?? 'مسند إليك: ' . $step->name;
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
            'step_id' => $this->step->id,
            'step_name' => $this->step->name,
            'message' => $this->message,
            'action_url' => route('step.show', $this->step->id),
            'type' => 'workflow_assignment'
        ];
    }
}
