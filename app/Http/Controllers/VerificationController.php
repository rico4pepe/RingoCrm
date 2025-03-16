<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Show the verification notice.
     */
    public function show()
    {
        return view('emails.verify-email');
    }

    /**
     * Handle email verification.
     */
    public function verify(Request $request, $id, $hash)
{
    $user = User::findOrFail($id);

    // ✅ Ensure the hash matches the user's email
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    // ✅ Check if the user is already verified
    if ($user->hasVerifiedEmail()) {
        return redirect('/login')->with('info', 'Your email is already verified.');
    }

    // ✅ Update email_verified_at
    $user->email_verified_at = now();
    $user->save();

    // ✅ Fire Laravel's email verification event
    event(new Verified($user));

    // ✅ Automatically log in the user after verification
    Auth::login($user);

    return redirect('/dashboard')->with('success', 'Email verified successfully! Welcome to your dashboard.');
}


    /**
     * Resend the email verification notification.
     */
    // public function resend(Request $request)
    // {
    //     $user = Auth::user();

    //     if ($user->email_verified_at) {
    //         return redirect('/dashboard');
    //     }

    //     $user->sendEmailVerificationNotification();

    //     return back()->with('success', 'Verification email sent again!');
    // }
}
