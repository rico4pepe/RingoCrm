<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Event;

class RegitrationController extends Controller
{

    public function showRegistrationform(){
        return view('register');
    }
    //
    public function registerUser(Request $request)
    {


           // ✅ Validate form input
           $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['Admin', 'others', 'SuperAdmin'])],
        ]);

          // Check if an admin is registering another user
          $loggedInUser = Auth::user();

          if ($loggedInUser) {
            // If the logged-in user has a prefix, assign it to the new user
            $prefix = explode('-', $loggedInUser->name)[0];
            $newUserName = $prefix . '-' . $request->name;
        } else {
            // New user (Admin) must provide their own prefix
            $prefix = 'Ringo'; // Default prefix for Ringo staff
            $newUserName = $prefix . '-' . $request->name;
        }

        $user = User::create([
            'name' => $newUserName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);




       // ✅ Fire event to send verification email
       event(new Registered($user));

     // Return the verify-email view and pass the verification URL
     return view('emails.verify-email', [
        'verificationUrl' => route('verification.notice'),
        'openInNewTab' => true  // Flag to indicate we want to open in new tab
    ]);
    }
}
