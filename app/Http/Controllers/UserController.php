<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index()
    {
        $loggedInUser = Auth::user();

        if (str_starts_with($loggedInUser->name, 'Ringo-')) {
            // If user is from Ringo, show all users
            $users = User::all();
        } else {
            // Otherwise, show only users with the same prefix
            $prefix = explode('-', $loggedInUser->name)[0];
            $users = User::where('name', 'like', "$prefix-%")->get();
        }
    
        return view('users', compact('users'));
    }
}
