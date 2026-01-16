<?php

namespace Database\Factories;

use App\Models\Step;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Step>
 */
class StepFactory extends Factory
{
    protected $model = Step::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'status' => 'draft',
            'workflow_id' => null,
            'current_stage_id' => null,
            'creator_id' => null,
            'assigned_user_id' => null,
            'priority' => fake()->numberBetween(1, 5),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'meta' => [],
        ];
    }

    /**
     * Indicate that the step is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'draft',
            'workflow_id' => null,
            'current_stage_id' => null,
        ]);
    }

    /**
     * Indicate that the step is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Indicate that the step was returned.
     */
    public function returned(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'returned',
        ]);
    }

    /**
     * Indicate that the step is completed.
     */
    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
            'current_stage_id' => null,
        ]);
    }

    /**
     * Indicate that the step is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
            'current_stage_id' => null,
        ]);
    }

    /**
     * Set the step as high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn(array $attributes) => [
            'priority' => 1,
        ]);
    }
}
