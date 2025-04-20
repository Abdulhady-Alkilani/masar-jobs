<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class JobOpportunity extends Model
{
    use HasFactory;

    protected $table = 'job_opportunities'; // Corrected spelling
    protected $primaryKey = 'JobID';

    protected $fillable = [
        'UserID', // The user (company manager/recruiter) who posted the job
        'Job Title', // Recommended: job_title
        'Job Description', // Recommended: job_description
        'Qualification',
        'Site', // Location
        'Date', // Posting date (use created_at?)
        'Skills', // Could be text, JSON, or ideally related to Skills table
        'Type', // (تدريب, وظيفة)
        'End Date', // Recommended: end_date
        'Status', // (مفعل, معلق, محذوف)
    ];

    protected $casts = [
        'Date' => 'date',
        'End Date' => 'date',
         // 'Skills' => 'array', // If storing skills as JSON
    ];

    /**
     * Get the user (recruiter/company) who posted the job opportunity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the applications for this job opportunity.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'JobID', 'JobID');
    }

    // Optional: If Skills column links to Skills table (Many-to-Many)
    // public function requiredSkills(): BelongsToMany
    // {
    //    return $this->belongsToMany(Skill::class, 'job_opportunity_skill', 'JobID', 'SkillID');
    // }
}