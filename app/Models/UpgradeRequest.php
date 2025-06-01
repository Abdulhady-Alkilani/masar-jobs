<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpgradeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'UserID',
        'requested_role',
        'status',
        'reason', // إذا أضفت حقل السبب في النموذج
        'admin_notes',
    ];

    /**
     * Get the user who submitted the request.
     */
    public function user(): BelongsTo
    {
        // تأكد أن المفتاح المحلي في users هو UserID
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}