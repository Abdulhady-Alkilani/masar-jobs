<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens; // <--- 1. إضافة استيراد التريت

class User extends Authenticatable
{
    // <--- 2. إضافة التريت إلى قائمة use داخل الكلاس
    use HasFactory, Notifiable, HasApiTokens;

    protected $primaryKey = 'UserID'; // تأكد أن هذا مطابق لجدولك

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
        ];
    }

    // --- Relationships ---

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'UserID', 'UserID'); // استخدام المفتاح الرئيسي الصحيح
    }

    public function skills(): BelongsToMany
    {
        // تم تعديل هذا ليتوافق مع الكود السابق (إذا كنت تستخدم نموذج Pivot)
        return $this->belongsToMany(Skill::class, 'user_skills', 'UserID', 'SkillID')
                    // ->using(UserSkill::class) // استخدم هذا إذا كان لديك نموذج UserSkill Pivot مخصص
                    ->withPivot('Stage');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'UserID', 'UserID');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'UserID', 'UserID');
    }

    public function jobOpportunities(): HasMany
    {
        return $this->hasMany(JobOpportunity::class, 'UserID', 'UserID');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'UserID', 'UserID');
    }

     public function createdTrainingCourses(): HasMany
     {
         return $this->hasMany(TrainingCourse::class, 'UserID', 'UserID');
     }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'UserID', 'UserID');
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(TrainingCourse::class, 'enrollments', 'UserID', 'CourseID')
                    ->withPivot('Status', 'Date', 'Complet Date')
                    ->withTimestamps();
    }
}