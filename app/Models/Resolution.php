<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'csv_path',
        'password',
        'mesasge',
        'user_id',

    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($resolution) {
            $resolution->reference_id = Str::uuid()->toString(); // Generates a unique UUID
        });
    }
}
