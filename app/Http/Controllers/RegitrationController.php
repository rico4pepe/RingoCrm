<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
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

        $user = User::create([
            'name' => $request->name,
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
