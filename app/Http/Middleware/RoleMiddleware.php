<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user()) {
            abort(403, 'Unauthorized');
        }

        // agar user ke paas required roles nahi hai
        if (! $request->user()->hasAnyRole($roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
