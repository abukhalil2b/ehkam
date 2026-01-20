<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
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
        // ========== RBAC GATE CONFIGURATION ==========

        // Super Admin Bypass: User ID 1 has ALL permissions
        Gate::before(function ($user, $ability) {
            if ($user->id === 1) {
                return true;
            }
        });

        // Define gates for all permissions dynamically
        Gate::define('*', function ($user, $ability) {
            return $user->hasPermission($ability);
        });

        // Register all permission slugs as gates
        try {
            $permissions = \App\Models\Permission::pluck('slug')->toArray();
            foreach ($permissions as $slug) {
                Gate::define($slug, function ($user) use ($slug) {
                    return $user->hasPermission($slug);
                });
            }
        } catch (\Exception $e) {
            // Database may not be ready during migrations
        }

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
