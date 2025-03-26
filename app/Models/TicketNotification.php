<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'is_read',
        'notification_type',
        'description'
    ];

    // Relationship with Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope to get unread notifications
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope to get notifications for current user
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }

    // Static method to create notification
    public static function createNotification($ticketId, $userId, $type = 'ticket_reply', $description = null)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'is_read' => false,
            'notification_type' => $type,
            'description' => $description ?? 'New ticket activity'
        ]);
    }
}
