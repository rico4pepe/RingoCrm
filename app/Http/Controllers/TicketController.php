<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client as DropboxClient;
use Illuminate\Support\Str;


class TicketController extends Controller
{
    //

    public function showTicketForm(){

        return view('create-tickets');
    }



    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'csv_file' => 'required|file|mimes:csv,txt,xls,xlsx,xlsm|max:2048',
        ]);

        // Upload CSV to Dropbox
        $filePath = 'tickets/' . uniqid() . '-' . $request->file('csv_file')->getClientOriginalName();
        Storage::disk('dropbox')->put($filePath, file_get_contents($request->file('csv_file')));

        // Create a Dropbox client
        $dropboxClient = new DropboxClient(config('filesystems.disks.dropbox.token'));

        // Generate a shared link for the uploaded file
        $sharedLink = $dropboxClient->createSharedLinkWithSettings($filePath);

        // Save in tickets table
        Ticket::create([
            'reference_id' => Str::uuid(),
            'csv_path' => $sharedLink['url'], // Use the shared link URL
            'message' => $request->message,
            'user_id' => Auth::id(),
            'status' => 'open',
            'title' => $request->title,
        ]);

        return back()->with('success', 'Ticket created successfully and file uploaded to Dropbox.');
    }



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

    return view('dashboard', compact('tickets'));
}

}
