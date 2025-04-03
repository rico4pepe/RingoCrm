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
        'mesasge', //
        'user_id',
        'status', // Add the status field
        'title', // Add the title field
        'assigned_user_id', // New field
        'severity', //  Add severity field
        'categories', //  Add categories field
        'supervisor_id', // Add supervisor field
        'closed_by_id', // Add closed_by_id field
        'sla_due_at', // Add sla_due_at field
        'closed_at', // Add closed_at field

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

public function supervisor()
{
    return $this->belongsTo(User::class, 'supervisor_id');
}

public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
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
