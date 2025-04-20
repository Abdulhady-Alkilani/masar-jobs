<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSkill extends Pivot // Extend Pivot for pivot models
{
    use HasFactory;

    protected $table = 'user_skills'; // Explicitly name the pivot table
    // protected $primaryKey = 'id'; // If using 'id' as PK
    // public $incrementing = true; // If using auto-incrementing 'id'

    public $timestamps = false; // Schema doesn't show timestamps for this table

    protected $fillable = [
        'UserID',
        'SkillID',
        'Stage',
    ];

    // Relationships to the related models (optional but can be useful)
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'SkillID', 'SkillID');
    }
}