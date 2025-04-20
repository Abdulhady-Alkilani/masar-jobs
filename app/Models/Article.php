<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles'; // Corrected spelling
    protected $primaryKey = 'ArticleID'; // Corrected spelling

    // Assuming timestamps are useful here (created_at for 'Date')
    // public $timestamps = true; // Default is true

    protected $fillable = [
        'UserID',
        'Title', // Corrected spelling
        'Description', // Corrected spelling
        'Date', // Can be handled by created_at or explicitly set
        'Type',
        'Article Photo', // Recommended: article_photo
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'Date' => 'date', // Cast the Date column to a Carbon date object
    ];

    /**
     * Get the user who created the article.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}