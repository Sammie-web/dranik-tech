<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware(\App\Http\Middleware\EnsureUserRole::class . ':customer')
     * or use multiple roles separated by | e.g. ':admin|provider'
     */
    public function handle(Request $request, Closure $next, string $roles = null)
    {
        $user = Auth::user();

        if (!$user) {
            // Not authenticated - let auth middleware handle redirects
            return redirect()->route('login');
        }

        if (!$roles) {
            // If no role specified, allow through
            return $next($request);
        }

        $allowed = explode('|', $roles);

        // Normalize role string
        $userRole = $user->role ?? null;

        // Admin is a superuser and should be allowed everywhere
        if ($userRole === 'admin') {
            return $next($request);
        }

        if (in_array($userRole, $allowed)) {
            return $next($request);
        }

        // If AJAX request, return 403 JSON
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden. Insufficient role.'], 403);
        }

        abort(403, 'Forbidden.');
    }
}
