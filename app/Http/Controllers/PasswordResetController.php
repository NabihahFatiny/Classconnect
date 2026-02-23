<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request.
     */
    public function sendResetLink(ForgotPasswordRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->input('email'))
            ->orWhere('username', $request->input('email'))
            ->first();

        if (! $user) {
            // Don't reveal if user exists for security
            return back()->with('status', 'If an account exists with that email/username, we have sent a password reset link.');
        }

        // Generate secure token
        $token = Str::random(64);

        // Delete any existing reset tokens for this user
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        // Create new reset token (expires in 10 minutes)
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
            'used_at' => null,
        ]);

        // In a real application, you would send an email here with the reset link
        // For development/testing, we'll show the reset link on the page
        // In production, uncomment the email sending code below and remove the redirect

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // For development: Show the reset link
        return back()->with([
            'status' => 'Password reset link has been generated!',
            'reset_link' => $resetUrl,
            'token' => $token, // For testing purposes only
        ]);

        // For production: Send email instead
        // Mail::to($user->email)->send(new PasswordResetMail($resetUrl));
        // return back()->with('status', 'If an account exists with that email/username, we have sent a password reset link.');

        // For production, use this instead:
        // Mail::to($user->email)->send(new PasswordResetMail($token));
        // return back()->with('status', 'If an account exists with that email/username, we have sent a password reset link.');
    }

    /**
     * Show the reset password form.
     */
    public function showResetPassword(string $token, ?string $email = null): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email ?? old('email'),
        ]);
    }

    /**
     * Handle password reset.
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        // Find the reset token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->whereNull('used_at')
            ->first();

        if (! $resetRecord) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token is expired (10 minutes)
        $createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(10)->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return back()->withErrors(['email' => 'This password reset token has expired. Please request a new one.']);
        }

        // Verify token
        if (! Hash::check($token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Find user
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Update password (stored as plain text as per user's requirement)
        $user->update(['password' => $password]);

        // Mark token as used (one-time use)
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->update(['used_at' => now()]);

        // Delete all reset tokens for this user
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        return redirect()->route('login')
            ->with('status', 'Your password has been reset successfully. Please login with your new password.');
    }
}


