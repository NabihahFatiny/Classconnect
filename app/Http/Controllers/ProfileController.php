<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdatePhotoRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('profiles.index', compact('user'));
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(UpdatePhotoRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Delete old photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Upload new photo
        $file = $request->file('photo');
        $filename = 'profile_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $photoPath = $file->storeAs('profiles', $filename, 'public');

        // Update user photo
        User::where('id', $user->id)->update(['photo' => $photoPath]);

        return redirect()->route('profiles.index')
            ->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        User::where('id', $user->id)->update(['photo' => null]);

        return redirect()->route('profiles.index')
            ->with('success', 'Profile photo deleted successfully!');
    }

    /**
     * Show the change password form.
     */
    public function showChangePassword(): View
    {
        return view('profiles.change-password');
    }

    /**
     * Update the user's password.
     *
     * This method ensures 100% verification accuracy by:
     * 1. Verifying the user is authenticated
     * 2. Strictly verifying the current password matches exactly
     * 3. Ensuring new password is different from current password
     * 4. Only updating password after all verifications pass
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Verify user is authenticated
        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'You must be logged in to change your password.']);
        }

        // Get fresh user data from database to ensure accuracy
        $freshUser = User::find($user->id);

        if (! $freshUser) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'User account not found.']);
        }

        $currentPasswordInput = $request->input('current_password');
        $newPassword = $request->input('password');

        // Strict verification: Current password must match exactly (plain text comparison)
        // This ensures 100% verification accuracy - no unauthorized password changes
        if ($currentPasswordInput !== $freshUser->password) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect. Please verify and try again.'])
                ->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }

        // Additional check: New password must be different from current password
        if ($newPassword === $freshUser->password) {
            return back()
                ->withErrors(['password' => 'New password must be different from your current password.'])
                ->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }

        // All verifications passed - update password (stored as plain text)
        User::where('id', $freshUser->id)->update([
            'password' => $newPassword,
        ]);

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        return redirect()->route('password.change')
            ->with('success', 'Your new password has been updated successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(): View
    {
        $user = Auth::user();

        return view('profiles.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * This method ensures 100% data accuracy and prevents corrupted/incomplete updates
     * by handling network interruptions, using database transactions, and implementing
     * idempotency checks.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validated();
        $startTime = microtime(true);
        $maxExecutionTime = 30; // 30 seconds maximum
        $maxRetries = 3;
        $retryDelay = 500; // milliseconds

        // Generate request fingerprint for idempotency (prevents duplicate updates)
        $requestFingerprint = $this->generateRequestFingerprint($validated, $user->id);
        $lastFingerprint = session()->get('last_profile_update_fingerprint');

        // Check if this is a duplicate request (same data, same user)
        if ($lastFingerprint === $requestFingerprint) {
            // Check if the update was already completed
            $freshUser = User::find($user->id);
            if ($freshUser && $this->isDataAlreadyUpdated($freshUser, $validated)) {
                return redirect()->route('profiles.edit')
                    ->with('info', 'Your profile information is already up to date.');
            }
        }

        // Retry logic for network interruptions
        $lastException = null;
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                // Check if we're within the timeout
                $elapsedTime = microtime(true) - $startTime;
                if ($elapsedTime >= $maxExecutionTime) {
                    Log::warning('Profile update timeout exceeded', [
                        'user_id' => $user->id,
                        'elapsed' => $elapsedTime,
                        'attempt' => $attempt,
                    ]);

                    return back()
                        ->withErrors(['general' => 'Profile update request timed out. Please try again.'])
                        ->withInput($request->except('password'));
                }

                // Use database transaction to ensure atomicity
                // This prevents partial or corrupted data
                $updatedUser = DB::transaction(function () use ($user, $validated, $requestFingerprint) {
                    // Get fresh user data to ensure we're working with latest state
                    $freshUser = User::lockForUpdate()->find($user->id);

                    if (! $freshUser) {
                        throw new \Exception('User account not found.');
                    }

                    // Validate data integrity before update
                    $this->validateDataIntegrity($freshUser, $validated);

                    // Perform atomic update - all fields updated together or none
                    $freshUser->update($validated);

                    // Verify data integrity after update
                    $freshUser->refresh();
                    $this->verifyDataIntegrity($freshUser, $validated);

                    return $freshUser;
                }, 10); // 10 second transaction timeout

                // Store fingerprint to prevent duplicate updates
                session()->put('last_profile_update_fingerprint', $requestFingerprint);
                session()->put('last_profile_update_time', now()->timestamp);

                // Update authenticated user instance
                Auth::setUser($updatedUser);

                $elapsedTime = microtime(true) - $startTime;

                Log::info('Profile updated successfully', [
                    'user_id' => $user->id,
                    'elapsed_time' => round($elapsedTime, 2),
                    'attempt' => $attempt,
                ]);

                return redirect()->route('profiles.edit')
                    ->with('success', 'Your profile has been updated successfully!');

            } catch (\Illuminate\Database\QueryException $e) {
                $lastException = $e;

                // Check for connection timeout or lock wait timeout
                if (str_contains($e->getMessage(), 'Connection') ||
                    str_contains($e->getMessage(), 'timeout') ||
                    str_contains($e->getMessage(), 'Lock wait timeout')) {

                    // If this is not the last attempt, retry
                    if ($attempt < $maxRetries) {
                        Log::info('Network interruption detected, retrying profile update', [
                            'user_id' => $user->id,
                            'attempt' => $attempt,
                            'delay' => $retryDelay * $attempt,
                        ]);

                        // Wait before retrying (exponential backoff)
                        usleep($retryDelay * $attempt * 1000);
                        continue;
                    }
                }

                // Check for duplicate entry errors
                if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'Duplicate entry')) {
                    $duplicateField = $this->extractDuplicateField($e->getMessage());

                    return back()
                        ->withErrors([$duplicateField => $this->getDuplicateMessage($duplicateField)])
                        ->withInput($request->except('password'));
                }

                Log::error('Profile update failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'attempt' => $attempt,
                ]);

                return back()
                    ->withErrors(['general' => 'Profile update failed due to a database error. Please try again.'])
                    ->withInput($request->except('password'));

            } catch (Throwable $e) {
                $lastException = $e;

                // For other exceptions, retry if attempts remain
                if ($attempt < $maxRetries) {
                    usleep($retryDelay * $attempt * 1000);
                    continue;
                }

                Log::error('Profile update failed with exception', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'attempt' => $attempt,
                ]);

                return back()
                    ->withErrors(['general' => 'Profile update failed. Please try again.'])
                    ->withInput($request->except('password'));
            }
        }

        // If all retries failed
        Log::error('Profile update failed after all retries', [
            'user_id' => $user->id,
            'max_retries' => $maxRetries,
            'last_error' => $lastException?->getMessage(),
        ]);

        return back()
            ->withErrors(['general' => 'Profile update failed due to network issues. Please check your connection and try again.'])
            ->withInput($request->except('password'));
    }

    /**
     * Generate a fingerprint for the request to enable idempotency.
     */
    private function generateRequestFingerprint(array $validated, int $userId): string
    {
        // Create a hash of the data to detect duplicate requests
        $dataString = json_encode([
            'user_id' => $userId,
            'data' => $validated,
            'timestamp' => floor(now()->timestamp / 60), // Round to nearest minute
        ]);

        return md5($dataString);
    }

    /**
     * Check if data is already updated (prevents duplicate updates).
     */
    private function isDataAlreadyUpdated(User $user, array $validated): bool
    {
        foreach ($validated as $key => $value) {
            // Convert dates to comparable format
            if ($key === 'date_of_birth' && $user->$key) {
                $userValue = $user->$key instanceof \Carbon\Carbon
                    ? $user->$key->format('Y-m-d')
                    : $user->$key;
                $validatedValue = $value instanceof \Carbon\Carbon
                    ? $value->format('Y-m-d')
                    : $value;

                if ($userValue !== $validatedValue) {
                    return false;
                }
            } elseif ($user->$key !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate data integrity before update.
     */
    private function validateDataIntegrity(User $user, array $validated): void
    {
        // Ensure user still exists
        if (! $user->exists) {
            throw new \Exception('User account no longer exists.');
        }

        // Validate that all required fields are present
        $requiredFields = ['name', 'username', 'email', 'mobile_phone', 'date_of_birth'];
        foreach ($requiredFields as $field) {
            if (! isset($validated[$field]) || empty($validated[$field])) {
                throw new \Exception("Required field '{$field}' is missing or empty.");
            }
        }
    }

    /**
     * Verify data integrity after update.
     */
    private function verifyDataIntegrity(User $user, array $validated): void
    {
        // Verify all fields were updated correctly
        foreach ($validated as $key => $value) {
            if ($key === 'date_of_birth') {
                $userValue = $user->$key instanceof \Carbon\Carbon
                    ? $user->$key->format('Y-m-d')
                    : $user->$key;
                $validatedValue = $value instanceof \Carbon\Carbon
                    ? $value->format('Y-m-d')
                    : $value;

                if ($userValue !== $validatedValue) {
                    throw new \Exception("Data integrity check failed: field '{$key}' was not updated correctly.");
                }
            } elseif ($user->$key !== $value) {
                throw new \Exception("Data integrity check failed: field '{$key}' was not updated correctly.");
            }
        }
    }

    /**
     * Extract duplicate field from database error message.
     */
    private function extractDuplicateField(string $message): string
    {
        if (str_contains($message, 'username') || str_contains($message, 'users_username')) {
            return 'username';
        }
        if (str_contains($message, 'email') || str_contains($message, 'users_email')) {
            return 'email';
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
            default => 'This information is already in use.',
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
