<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\CalendarEvent::observe(\App\Observers\CalendarEventObserver::class);

        // ========== WORKFLOW EVENT LISTENERS ==========
        $listener = new \App\Listeners\NotifyTeamMembers();

        Event::listen(
            \App\Events\StepSubmitted::class,
            [$listener, 'handleStepSubmitted']
        );

        Event::listen(
            \App\Events\StepApproved::class,
            [$listener, 'handleStepApproved']
        );

        Event::listen(
            \App\Events\StepReturned::class,
            [$listener, 'handleStepReturned']
        );

        Event::listen(
            \App\Events\StepRejected::class,
            [$listener, 'handleStepRejected']
        );
    }
}
