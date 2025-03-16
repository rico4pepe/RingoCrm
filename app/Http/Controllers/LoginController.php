<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('index');
    }

    public function login(Request $request)
{
    // ✅ Validate form input
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    // Attempt to log in user
    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {

        $user = Auth::user();

        // ✅ Check for email verification
        if (!$user->email_verified_at) {
            Log::error('Login failed: Invalid credentials');
            Auth::logout();
            return back()->with('error', 'Please verify your email before logging in.');
        }

        Log::info('User login successful: ' . $user->email);
        Log::info('User verified: ' . ($user->email_verified_at ? 'true' : 'false'));

        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }



    // Add this: Handle failed login attempts
    return back()
        ->withInput($request->only('email', 'remember'))
        ->with('error', 'These credentials do not match our records.');
}
}
