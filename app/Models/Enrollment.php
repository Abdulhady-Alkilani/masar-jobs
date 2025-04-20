<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model; // Use Pivot if it's mainly a pivot
use Illuminate\Database\Eloquent\Relations\Pivot; // Better for pivot tables
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Enrollment extends Pivot // Extending Pivot
{
    use HasFactory;

    protected $table = 'enrollments'; // Corrected spelling
    // protected $primaryKey = 'EnrollmentID'; // Use if you have this specific PK
    public $incrementing = true; // Laravel default Pivot has increments false, set true if using auto PK 'id' or 'EnrollmentID'

     // If using 'EnrollmentID' as primary key instead of default 'id' for pivot
     protected $primaryKey = 'EnrollmentID';


    protected $fillable = [
        'UserID',
        'CourseID',
        'Status', // (مكتمل, قيد التقدم, ملغي)
        'Date', // Enrollment date (use created_at?)
        'Complet Date', // Recommended: complete_date, Nullable
    ];

    protected $casts = [
        'Date' => 'date',
        'Complet Date' => 'date', // Corrected spelling
    ];

     // Define relationships back to User and TrainingCourse
     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class, 'UserID', 'UserID');
     }

     public function trainingCourse(): BelongsTo
     {
         return $this->belongsTo(TrainingCourse::class, 'CourseID', 'CourseID');
     }
}