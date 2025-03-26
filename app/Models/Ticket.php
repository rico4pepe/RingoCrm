<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'csv_path', // Or remove if using separate attachments table
        'mesasge', // Or 'message' if you prefer
        'user_id',
        'status', // Add the status field
        'title', // Add the title field
        'assigned_user_id', // New field
        'severity', //  Add severity field
    ];


    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function replies()
{
    return $this->hasMany(TicketReply::class, 'ticket_id');
}

public function assignedUser()
{
    return $this->belongsTo(User::class, 'assigned_user_id');
}



public function hasUnreadReplies()
{
    return $this->replies()->where('is_read', false)->exists();
}

public function getUnreadRepliesCount()
{
    return $this->replies()->where('is_read', false)->count();
}

}
