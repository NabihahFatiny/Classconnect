<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to refresh session on activity.
 *
 * This ensures that sessions remain active during rapid navigation
 * by refreshing the session lifetime on every authenticated request.
 * Prevents unexpected logouts during active use.
 */
class RefreshSessionOnActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only refresh session for authenticated users
        if (Auth::check()) {
            // Touch the session to update last_activity timestamp
            // This extends the session lifetime on every request
            $request->session()->put('last_activity', now()->timestamp);

            // Regenerate session ID periodically to prevent session fixation
            // Only regenerate every 10 requests to avoid overhead
            if (! $request->session()->has('regeneration_count')) {
                $request->session()->put('regeneration_count', 0);
            }

            $regenerationCount = $request->session()->get('regeneration_count', 0);
            if ($regenerationCount % 10 === 0 && $regenerationCount > 0) {
                $request->session()->regenerate();
            }

            $request->session()->put('regeneration_count', $regenerationCount + 1);
        }

        return $next($request);
    }
}
