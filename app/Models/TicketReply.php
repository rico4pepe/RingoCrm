<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'user_id', 'message', 'is_read'];

    protected static function boot()
    {
        parent::boot();
    
        static::created(function ($reply) {
            $ticket = $reply->ticket;
            $replyingUser = $reply->user;
            $ticketOwner = $ticket->user;
    
            if (!$replyingUser || !$ticketOwner) {
                Log::error('Replying user or ticket owner is missing', [
                    'ticket_id' => $ticket->id ?? null,
                    'replying_user_id' => $replyingUser->id ?? null
                ]);
                return;
            }
    
            // Mark previous notifications as unread for other users
            TicketNotification::where('ticket_id', $ticket->id)
                ->where('user_id', '!=', $replyingUser->id) 
                ->update(['is_read' => false]);
    
            // Extract prefixes from user names
            $replyingUserPrefix = explode('-', $replyingUser->name)[0] ?? '';
            $ticketOwnerPrefix = explode('-', $ticketOwner->name)[0] ?? '';
    
            // Determine who to notify
            $notifyUsers = collect();
    
            // If Ringo replies, notify the original ticket owner (Shago, MTN, Hydrogen)
            if (Str::startsWith($replyingUser->name, 'Ringo-')) {
                $notifyUsers->push($ticketOwner);
            }
            // If a non-Ringo user (Shago, MTN, Hydrogen) replies, notify all Ringo users
            else {
                $notifyUsers = User::where('name', 'LIKE', 'Ringo-%')->get();
            }
    
            // Send notifications
            foreach ($notifyUsers as $notifyUser) {
                if ($notifyUser->id !== $replyingUser->id) {
                    TicketNotification::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $notifyUser->id,
                        'is_read' => false,
                        'notification_type' => 'ticket_reply',
                        'description' => "New reply to Ticket #{$ticket->reference_id}"
                    ]);
    
                    Log::info('Ticket Reply Notification Created', [
                        'ticket_id' => $ticket->id,
                        'replying_user' => $replyingUser->name,
                        'notified_user' => $notifyUser->name,
                    ]);
                }
            }
        });
    }
    


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    protected $attributes = [
        'is_read' => false, // Ensure new replies are unread by default
    ];
}
