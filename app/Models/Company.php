<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'CompanyID';

    protected $fillable = [
        'UserID', 'Name', 'Email', 'Phone', 'Description',
        'Country', 'City', 'Detailed Address', 'Media', 'Web site', 'Status',
    ];

    // ... (علاقة user) ...
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    /**
     * !!! علاقة الشركة بفرص العمل التي نشرتها (الآن تعمل بشكل صحيح) !!!
     */
    public function jobOpportunities(): HasMany
    {
        // تربط CompanyID في هذا المودل (Company)
        // مع CompanyID في مودل JobOpportunity
        return $this->hasMany(JobOpportunity::class, 'CompanyID', 'CompanyID');
    }

    /**
     * علاقة الشركة بالدورات (إذا كانت تنشر دورات)
     * يجب تطبيق نفس منطق CompanyID على جدول الدورات أيضًا
     */
    // public function trainingCourses(): HasMany
    // {
    //     return $this->hasMany(TrainingCourse::class, 'CompanyID', 'CompanyID');
    // }
}