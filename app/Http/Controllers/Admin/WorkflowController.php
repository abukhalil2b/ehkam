<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkflowTransition;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all workflow transitions across all models
        $allTransitions = WorkflowTransition::with(['workflowable', 'toStage', 'actor'])
            ->latest()
            ->get()
            ->map(function ($transition) {
                $model = $transition->workflowable;
                $modelType = class_basename($transition->workflowable_type);

                return [
                    'type' => $modelType,
                    'name' => $model->name ?? 'N/A',
                    'stage' => $transition->toStage->name ?? '—',
                    'action' => $transition->action_label,
                    'last_updated' => $transition->created_at,
                    'actor' => $transition->actor->name ?? '—',
                    'link' => $this->getModelLink($model, $modelType),
                ];
            });

        return view('admin.workflow.index', compact('allTransitions'));
    }

    private function getModelLink($model, $modelType)
    {
        return match ($modelType) {
            'Step' => route('step.show', $model->id),
            'AimSectorFeedback' => route('aim_sector_feedback.show', $model->id),
            default => '#',
        };
    }
}
