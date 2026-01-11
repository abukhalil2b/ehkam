<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionTaskAssigned extends Notification
{
    use Queueable;

    public $task;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $message = null)
    {
        $this->task = $task;
        $this->message = $message ?? 'مهمة جديدة: ' . $task->title;
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
            'task_id' => $this->task->id,
            'mission_id' => $this->task->mission_id,
            'task_title' => $this->task->title,
            'message' => $this->message,
            // Assuming we have a route to show the mission/task
            'action_url' => route('missions.task.show', $this->task->mission_id),
            'type' => 'mission_task_assignment'
        ];
    }
}
