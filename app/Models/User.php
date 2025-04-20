<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable // Optional: implements MustVerifyEmail if using email verification feature
{
    use HasFactory, Notifiable;
    protected $primaryKey = 'UserID';
    /**
     * The table associated with the model.
     *
     * If your table name doesn't follow Laravel's convention (plural snake_case),
     * uncomment and set the table name explicitly.
     * // protected $table = 'users';
     */

    /**
      * The primary key associated with the table.
      * Default is 'id'. Uncomment and change if using 'UserID'.
      * // protected $primaryKey = 'UserID';
      * // public $incrementing = false; // If primary key is not auto-incrementing
      * // protected $keyType = 'string'; // If primary key is not an integer
      */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'phone',
        'photo',
        'status',
        'type',
        // 'email_verified_at', // Handled separately or during registration/verification logic
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // 'sign_up_date' => 'date', // If you add this column explicitly
        ];
    }

    // --- Relationships ---

    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'UserID'); // Adjust FK if needed
    }

    /**
     * The skills that belong to the user.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills', 'UserID', 'SkillID')
                    ->using(UserSkill::class) // <--- أخبر العلاقة باستخدام نموذج Pivot هذا
                    ->withPivot('Stage');     // احتفظ بالبيانات الإضافية
                    // لا تستخدم ->withTimestamps() هنا لأن النموذج UserSkill يعطله
    }

    /**
     * Get the articles created by the user.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'UserID'); // Adjust FK if needed
    }

    /**
     * Get the company managed by the user. (Assuming one user manages one company)
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'UserID'); // Adjust FK if needed
    }

    /**
     * Get the job opportunities created by the user.
     */
    public function jobOpportunities(): HasMany
    {
        return $this->hasMany(JobOpportunity::class, 'UserID'); // Adjust FK if needed
    }

    /**
     * Get the job applications submitted by the user.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'UserID'); // Adjust FK if needed
    }

     /**
      * Get the training courses created by the user.
      */
     public function createdTrainingCourses(): HasMany
     {
         return $this->hasMany(TrainingCourse::class, 'UserID'); // Adjust FK if needed
     }

    /**
     * Get the enrollments for the user.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'UserID'); // Adjust FK if needed
    }

    /**
     * The training courses the user is enrolled in.
     */
    public function enrolledCourses(): BelongsToMany
    {
        // Assuming 'enrollments' is the pivot table name
        return $this->belongsToMany(TrainingCourse::class, 'enrollments', 'UserID', 'CourseID')
                    ->withPivot('Status', 'Date', 'Complet Date') // Include extra pivot data
                    ->withTimestamps(); // If pivot table has timestamps
    }
}