<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TrainingCourse extends Model
{
    use HasFactory;

    protected $table = 'training_courses'; // Corrected spelling
    protected $primaryKey = 'CourseID';

    protected $fillable = [
        'UserID', // User who created/manages the course
        'Course name', // Recommended: course_name
        'Trainers name', // Recommended: trainers_name (or trainer_name if single)
        'Course Description', // Recommended: course_description
        'Site', // (حضوري, اونلاين)
        'Trainers Site', // Recommended: trainers_site (Training provider/platform)
        'Start Date', // Recommended: start_date
        'End Date', // Recommended: end_date
        'Enroll Hyper Link', // Recommended: enroll_hyperlink
        'Stage', // (مبتدئ, متوسط, متقدم)
        'Certificate', // (يوجد, لا يوجد) - boolean might be better
    ];

    protected $casts = [
        'Start Date' => 'date',
        'End Date' => 'date',
        // 'Certificate' => 'boolean', // If changing to boolean
    ];

    /**
     * Get the user who created the course.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * Get the enrollments for this course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'CourseID', 'CourseID');
    }

     /**
      * The users enrolled in this course.
      */
     public function enrolledUsers(): BelongsToMany
     {
         return $this->belongsToMany(User::class, 'enrollments', 'CourseID', 'UserID')
                     ->withPivot('Status', 'Date', 'Complet Date')
                     ->withTimestamps(); // If pivot table has timestamps
     }
}