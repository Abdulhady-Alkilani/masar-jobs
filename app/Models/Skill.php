<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';
    protected $primaryKey = 'SkillID';
    public $timestamps = false; // No created_at/updated_at needed based on schema

    protected $fillable = [
        'Name',
    ];

    /**
     * The users that possess the skill.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills', 'SkillID', 'UserID')
                    ->using(UserSkill::class)
                    ->withPivot('Stage');
    }
}