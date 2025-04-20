<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'CompanyID';

    protected $fillable = [
        'UserID',
        'Name',
        'Email',
        'Phone',
        'Description',
        'Country', // Corrected spelling
        'City',
        'Detailed Address', // Recommended: detailed_address
        'Media', // Path/URL or JSON array of paths/URLs
        'Web site', // Recommended: website
    ];

    /**
     * Get the user who manages the company.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}