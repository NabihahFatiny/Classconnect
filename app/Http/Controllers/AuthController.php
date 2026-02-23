<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    public function showLogin(): RedirectResponse|View
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Show login page for guests
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');
        $userType = $request->input('user_type');

        $user = User::where('username', $credentials['username'])
            ->where('user_type', $userType)
            ->first();

        // Check if account is locked
        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            $minutesRemaining = now()->diffInMinutes($user->locked_until);
            return back()->withErrors([
                'username' => "Your account has been locked due to multiple failed login attempts. Please try again in {$minutesRemaining} minute(s).",
            ])->onlyInput('username');
        }

        // If lock period has expired, reset the lock
        if ($user && $user->locked_until && $user->locked_until->isPast()) {
            $user->update([
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ]);
        }

        // Compare plain text passwords
        if ($user && $credentials['password'] === $user->password) {
            // Reset failed login attempts on successful login
            $user->update([
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ]);

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Increment failed login attempts
        if ($user) {
            $failedAttempts = ($user->failed_login_attempts ?? 0) + 1;
            $maxAttempts = 5;
            $lockoutMinutes = 30;

            if ($failedAttempts >= $maxAttempts) {
                // Lock the account for 30 minutes
                $user->update([
                    'failed_login_attempts' => $failedAttempts,
                    'locked_until' => now()->addMinutes($lockoutMinutes),
                ]);

                return back()->withErrors([
                    'username' => "Your account has been locked after {$maxAttempts} failed login attempts. Please try again in {$lockoutMinutes} minutes.",
                ])->onlyInput('username');
            } else {
                // Just increment the counter
                $user->update([
                    'failed_login_attempts' => $failedAttempts,
                ]);

                $remainingAttempts = $maxAttempts - $failedAttempts;
                return back()->withErrors([
                    'username' => "The provided credentials do not match our records. {$remainingAttempts} attempt(s) remaining before account lockout.",
                ])->onlyInput('username');
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Handle user registration with database delay handling.
     *
     * This method ensures zero duplicate accounts by:
     * 1. Checking for existing users before registration
     * 2. Using database transactions for atomicity
     * 3. Implementing retry logic for database delays
     * 4. Handling timeouts (10 seconds max)
     * 5. Preventing duplicate account creation
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $startTime = microtime(true);
        $maxExecutionTime = 10; // 10 seconds maximum
        $maxRetries = 3;
        $retryDelay = 500; // milliseconds

        // Pre-check for duplicates before attempting registration
        // This prevents unnecessary database operations
        $duplicateCheck = $this->checkForDuplicates($validated);
        if ($duplicateCheck['exists']) {
            return back()
                ->withErrors([$duplicateCheck['field'] => $duplicateCheck['message']])
                ->withInput($request->except('password'));
        }

        // Retry logic for database delays
        $lastException = null;
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                // Check if we're within the 10-second timeout
                $elapsedTime = microtime(true) - $startTime;
                if ($elapsedTime >= $maxExecutionTime) {
                    Log::warning('Registration timeout exceeded', [
                        'elapsed' => $elapsedTime,
                        'attempt' => $attempt,
                    ]);

                    return back()
                        ->withErrors(['general' => 'Registration request timed out. Please try again.'])
                        ->withInput($request->except('password'));
                }

                // Use database transaction to ensure atomicity
                $user = DB::transaction(function () use ($validated, $request) {
                    // Double-check for duplicates within transaction (race condition protection)
                    $duplicateCheck = $this->checkForDuplicates($validated);
                    if ($duplicateCheck['exists']) {
                        throw new \Exception($duplicateCheck['message']);
                    }

                    // Create user within transaction
                    $user = User::create($validated);

                    return $user;
                }, 5); // 5 second transaction timeout

                // Registration successful
                Auth::login($user);
                $elapsedTime = microtime(true) - $startTime;

                Log::info('User registered successfully', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'elapsed_time' => round($elapsedTime, 2),
                    'attempt' => $attempt,
                ]);

                return redirect()->route('dashboard')
                    ->with('success', 'Registration successful! Welcome to ClassConnect.');

            } catch (\Illuminate\Database\QueryException $e) {
                $lastException = $e;

                // Check for duplicate entry error (MySQL error code 1062)
                if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'Duplicate entry')) {
                    // Extract which field caused the duplicate
                    $duplicateField = $this->extractDuplicateField($e->getMessage(), $validated);

                    Log::warning('Duplicate registration attempt prevented', [
                        'field' => $duplicateField,
                        'attempt' => $attempt,
                    ]);

                    return back()
                        ->withErrors([$duplicateField => $this->getDuplicateMessage($duplicateField)])
                        ->withInput($request->except('password'));
                }

                // Check for connection timeout or lock wait timeout
                if (str_contains($e->getMessage(), 'Connection') ||
                    str_contains($e->getMessage(), 'timeout') ||
                    str_contains($e->getMessage(), 'Lock wait timeout')) {

                    // If this is not the last attempt, retry
                    if ($attempt < $maxRetries) {
                        Log::info('Database delay detected, retrying registration', [
                            'attempt' => $attempt,
                            'delay' => $retryDelay * $attempt, // Exponential backoff
                        ]);

                        // Wait before retrying (exponential backoff)
                        usleep($retryDelay * $attempt * 1000);
                        continue;
                    }
                }

                // Log the error and return
                Log::error('Registration failed', [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt,
                ]);

                return back()
                    ->withErrors(['general' => 'Registration failed due to a database error. Please try again.'])
                    ->withInput($request->except('password'));

            } catch (Throwable $e) {
                $lastException = $e;

                // If duplicate check within transaction found a duplicate
                if (str_contains($e->getMessage(), 'already') ||
                    str_contains($e->getMessage(), 'taken') ||
                    str_contains($e->getMessage(), 'registered')) {

                    return back()
                        ->withErrors(['general' => $e->getMessage()])
                        ->withInput($request->except('password'));
                }

                // For other exceptions, retry if attempts remain
                if ($attempt < $maxRetries) {
                    usleep($retryDelay * $attempt * 1000);
                    continue;
                }

                Log::error('Registration failed with exception', [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt,
                ]);

                return back()
                    ->withErrors(['general' => 'Registration failed. Please try again.'])
                    ->withInput($request->except('password'));
            }
        }

        // If all retries failed
        Log::error('Registration failed after all retries', [
            'max_retries' => $maxRetries,
            'last_error' => $lastException?->getMessage(),
        ]);

        return back()
            ->withErrors(['general' => 'Registration failed due to database delays. Please try again in a moment.'])
            ->withInput($request->except('password'));
    }

    /**
     * Check for duplicate users before registration.
     *
     * @return array{exists: bool, field?: string, message?: string}
     */
    private function checkForDuplicates(array $validated): array
    {
        // Check username
        if (isset($validated['username']) &&
            User::where('username', $validated['username'])->exists()) {
            return [
                'exists' => true,
                'field' => 'username',
                'message' => 'This username is already taken.',
            ];
        }

        // Check email
        if (isset($validated['email']) &&
            User::where('email', $validated['email'])->exists()) {
            return [
                'exists' => true,
                'field' => 'email',
                'message' => 'This email is already registered.',
            ];
        }

        // Check user_id
        if (isset($validated['user_id']) &&
            User::where('user_id', $validated['user_id'])->exists()) {
            return [
                'exists' => true,
                'field' => 'user_id',
                'message' => 'This user ID is already registered.',
            ];
        }

        return ['exists' => false];
    }

    /**
     * Extract the duplicate field from database error message.
     */
    private function extractDuplicateField(string $message, array $validated): string
    {
        if (str_contains($message, 'username') || str_contains($message, 'users_username')) {
            return 'username';
        }
        if (str_contains($message, 'email') || str_contains($message, 'users_email')) {
            return 'email';
        }
        if (str_contains($message, 'user_id') || str_contains($message, 'users_user_id')) {
            return 'user_id';
        }

        return 'general';
    }

    /**
     * Get user-friendly duplicate error message.
     */
    private function getDuplicateMessage(string $field): string
    {
        return match ($field) {
            'username' => 'This username is already taken.',
            'email' => 'This email is already registered.',
            'user_id' => 'This user ID is already registered.',
            default => 'This information is already registered.',
        };
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}


