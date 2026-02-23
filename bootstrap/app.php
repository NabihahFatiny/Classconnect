<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Redirect guests trying to access protected routes to login
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Refresh session on activity for authenticated users
        // This ensures sessions remain active during rapid navigation
        // $middleware->web(append: [
        //     \App\Http\Middleware\RefreshSessionOnActivity::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token mismatch (419 errors)
        $exceptions->render(function (Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your session has expired. Please refresh the page and try again.'], 419);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['_token' => 'Your session has expired. Please refresh the page and try again.']);
        });
    })->create();
