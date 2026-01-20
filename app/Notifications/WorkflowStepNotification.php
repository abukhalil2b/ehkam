<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class WorkflowStepNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Model $model;
    protected string $action;
    protected string $message;
    protected User $actor;

    /**
     * Create a new notification instance.
     */
    public function __construct(Model $model, string $action, string $message, User $actor)
    {
        $this->model = $model;
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
        // Polymorphic safe access
        $workflowName = $this->model->workflowInstance?->workflow?->name;
        $stageName = $this->model->workflowInstance?->currentStage?->name;

        // Fallback for ID/Name if model doesn't have standard fields (though generic Model has ID)
        $id = $this->model->id;
        $name = $this->model->title ?? $this->model->name ?? 'Element #' . $id;

        return [
            'type' => 'workflow_step',
            'action' => $this->action,
            'message' => $this->message,
            'step_id' => $id,
            'step_name' => $name,
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'current_stage' => $stageName,
            'workflow' => $workflowName,
            'link' => method_exists($this->model, 'getShowRoute') ? $this->model->getShowRoute() : null, // Future proofing
            'entity_type' => $this->model->getMorphClass(),
        ];
    }

    /**
     * Get the mail representation of the notification (optional).
     */
    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->model->title ?? $this->model->name ?? 'Element';

        // Construct URL - hardcoded to steps for now but should be dynamic
        // If Activity, maybe project route? For now preserving existing behavior but safer
        $url = url('/steps/' . $this->model->id);
        if ($this->model instanceof \App\Models\Activity) {
            $url = route('project.show', $this->model->project_id ?? 0);
        }

        return (new MailMessage)
            ->subject('تحديث سير العمل: ' . $name)
            ->line($this->message)
            ->line('العنصر: ' . $name)
            ->line('بواسطة: ' . $this->actor->name)
            ->action('عرض التفاصيل', $url);
    }
}
