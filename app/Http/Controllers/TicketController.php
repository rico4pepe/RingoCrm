<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;
//use Illuminate\Support\Facades\Storage;
//use Spatie\Dropbox\Client as DropboxClient;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\TicketReply;


class TicketController extends Controller
{
    //

    public function showTicketForm(){

        return view('create-tickets');
    }

    public function showmyForm(){

        return view('respond-ticket');
    }

    

    



    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'csv_file' => 'nullable|file|mimes:csv,txt,xls,xlsx,xlsm|max:2048'
    ]);

    $fileUrl = null;

     // Check if a file was uploaded before processing
     if ($request->hasFile('csv_file')) {
        $file = $request->file('csv_file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Upload with custom public_id to preserve extension
        $uploadedFile = Cloudinary::uploadFile($file->getRealPath(), [
            'folder' => 'tickets',
            'resource_type' => 'raw',
            'public_id' => pathinfo($originalName, PATHINFO_FILENAME), // Use filename without extension
            'format' => $extension // Specify the format/extension
        ]);

        // Get the URL of the uploaded file
        $fileUrl = $uploadedFile->getSecurePath();
    }

    // Save in tickets table
    Ticket::create([
        'reference_id' => Str::uuid(),
        'csv_path' => $fileUrl, // Will be NULL if no file was uploaded
        'mesasge' => $request->message, // Fixed typo (was "mesasge")
        'user_id' => Auth::id(),
        'status' => 'open',
        'title' => $request->title,
    ]);

    return back()->with('success', 'Ticket created successfully.');
}



    public function viewTickets()
{
    $loggedInUser = Auth::user();

    // SuperAdmins (Ringo-) can view all tickets
    if (str_starts_with($loggedInUser->name, 'Ringo-')) {
        $tickets = Ticket::with('user')->get(); // Get all tickets
    } else {
         // Extract prefix from the logged-in user's name
         $prefix = explode('-', $loggedInUser->name)[0];

         // Get all user IDs that share the same prefix from the users table
         $userIds = User::where('name', 'like', "$prefix-%")->pluck('id')->toArray();
 

       // This will dump the $tickets variable and stop execution
         // Fetch tickets that belong to those users
         $tickets = Ticket::whereIn('user_id', $userIds)->get();
    }

   // dd($tickets); 

    return view('dashboard', compact('tickets'));
    
}


public function replyToTicket(Request $request, $ticketId)
{
    $request->validate([
        'message' => 'required|string',
        'status' => 'nullable|in:open,in_progress,resolved,closed' // Make status optional
    ]);

    $ticket = Ticket::findOrFail($ticketId);
    $loggedInUser = Auth::user();
    $userPrefix = explode('-', $loggedInUser->name)[0];

    // If the user is not Ringo-, ensure they can only reply to tickets within their prefix
    if (!str_starts_with($loggedInUser->name, 'Ringo-')) {
        $ticketOwner = $ticket->user;
        $ticketOwnerPrefix = explode('-', $ticketOwner->name)[0];

        if ($ticketOwnerPrefix !== $userPrefix) {
            return redirect()->back()->with('error', 'You can only reply to tickets from your company.');
        }
    }

    // Save the reply
    TicketReply::create([
        'ticket_id' => $ticket->id,
        'user_id' => $loggedInUser->id,
        'message' => $request->message,
    ]);

      // ✅ Update the ticket status **ONLY IF** a status is provided
      if ($request->filled('status')) {
        $ticket->update(['status' => $request->status]);
    }
    return redirect()->back()->with('success', 'Reply added successfully.');
}


public function showRespondTicketForm($ticketId)
{
    $ticket = Ticket::with(['user', 'replies.user'])->findOrFail($ticketId);

    // Restrict users to only see their company's tickets
    $loggedInUser = Auth::user();
    if (!str_starts_with($loggedInUser->name, 'Ringo-')) {
        $ticketOwnerPrefix = explode('-', $ticket->user->name)[0];
        $userPrefix = explode('-', $loggedInUser->name)[0];

        if ($ticketOwnerPrefix !== $userPrefix) {
            return redirect()->route('dashboard')->with('error', 'You are not allowed to view this ticket.');
        }
    }

    return view('respond-ticket', compact('ticket'));
}


public function assignTicket($ticketId)
{
    $loggedInUser = Auth::user();

    // Ensure user has "Ringo-" prefix AND "SuperAdmin" role
    if (!(Str::startsWith($loggedInUser->name, 'Ringo-') && $loggedInUser->role === 'SuperAdmin')) {
        return redirect()->back()->with('error', 'Unauthorized: Only Ringo- SuperAdmins can assign tickets.');
    }

    $ticket = Ticket::findOrFail($ticketId);
   // Get all users with "Ringo-" prefix
   $ringoUsers = User::where('name', 'like', 'Ringo-%')->get();

    return view('assign-tickets',  compact('ticket', 'ringoUsers'));
}

public function updateAssign(Request $request, $ticketId)
{
    $request->validate([
        'assigned_user_id' => 'required|exists:users,id',
    ]);

    $ticket = Ticket::findOrFail($ticketId);
    $ticket->assigned_user_id = $request->assigned_user_id;
    $ticket->save();

    return redirect()->route('dashboard')->with('success', 'Ticket assigned successfully.');
}


}
