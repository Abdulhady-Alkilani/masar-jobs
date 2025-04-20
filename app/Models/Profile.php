<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles'; // Explicitly state table name
    protected $primaryKey = 'ProfileID'; // Explicitly state primary key

    // Disable auto-incrementing if ProfileID is not meant to be auto-increment
    // public $incrementing = false;

    // Disable timestamps if not using created_at/updated_at
    // public $timestamps = false;

    protected $fillable = [
        'UserID', // Make sure FK is fillable or set via relationship
        'University',
        'GPA', // Corrected from GDP
        'Personal Description', // Keep space or use snake_case? Recommended: personal_description
        'Technical Description', // Recommended: technical_description
        'Git Hyper Link', // Recommended: git_hyperlink
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID'); // Foreign Key, Owner Key
    }
}