<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $primaryKey = 'GroupID';
    public $timestamps = false; // Schema doesn't indicate timestamps

    protected $fillable = [
        'Telegram Hyper Link', // Recommended: telegram_hyperlink
        // Add other fields if groups have names, descriptions, etc.
    ];
}