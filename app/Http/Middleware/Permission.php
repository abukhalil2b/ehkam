<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Permission {
    public function handle(Request $request, Closure $next, $slug) {
        $user = $request->user();

        // The user must be authenticated and have the specific permission slug.
        // The hasPermission method handles the Super Admin (ID 1) bypass.
        if ($user && $user->hasPermission($slug)) {
            return $next($request);
        }

        // If the user is not authenticated or lacks permission, deny access.
        abort(403, 'Unauthorized action. You do not have the required permission.');
    }
}
