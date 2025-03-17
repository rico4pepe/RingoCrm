<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;


class TicketController extends Controller
{
    //

    public function viewTickets()
{
    $loggedInUser = Auth::user();

    // SuperAdmins (Ringo-) can view all tickets
    if (str_starts_with($loggedInUser->name, 'Ringo-')) {
        $tickets = Ticket::all(); // Get all tickets
    } else {
        // Other users only see tickets related to their prefix
        $prefix = explode('-', $loggedInUser->name)[0];

        // Get all user IDs that share the same prefix
        $userIds = User::where('name', 'like', "$prefix-%")->pluck('id');

        // Get tickets belonging to those users
        $tickets = Ticket::whereIn('user_id', $userIds)->get();
    }

    return view('tickets', compact('tickets'));
}

}
