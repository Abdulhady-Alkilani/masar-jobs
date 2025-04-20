<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $table = 'job_applications'; // Default convention based on model name
    // protected $primaryKey = 'ID'; // Explicitly if needed, Laravel defaults to 'id'

    // If using 'ID' as primary key instead of 'id'
    protected $primaryKey = 'ID';

    protected $fillable = [
        'UserID', // User applying
        'JobID', // Job being applied for
        'Status',
        'Date', // Application date (use created_at?)
        'Description', // Cover letter/notes
        'CV', // Path to CV file
    ];

    protected $casts = [
        'Date' => 'date',
    ];

    /**
     * Get the user who applied.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the job opportunity being applied for.
     */
    public function jobOpportunity(): BelongsTo
    {
        return $this->belongsTo(JobOpportunity::class, 'JobID', 'JobID');
    }
}