<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'attachment_path', // Or remove if using separate attachments table
        'description', // Or 'message' if you prefer
        'user_id',
        'status', // Add the status field
        'title', // Add the title field
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            $ticket->reference_id = Str::uuid()->toString(); // Generates a unique UUID
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
