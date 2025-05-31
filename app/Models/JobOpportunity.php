<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOpportunity extends Model
{
    use HasFactory;

    protected $table = 'job_opportunities';
    protected $primaryKey = 'JobID';

    /**
     * السمات القابلة للملء بشكل جماعي.
     */
    protected $fillable = [
        'UserID',       // المستخدم المنشئ (مدير/أدمن)
        'CompanyID',    // !!! المفتاح الأجنبي للشركة !!!
        'Job Title',
        'Job Description',
        'Qualification',
        'Site',
        'Date',
        'Skills',
        'Type',
        'End Date',
        'Status',
    ];

    /**
     * السمات التي يجب تحويلها.
     */
    protected $casts = [
        'Date' => 'datetime',
        'End Date' => 'date',
        // 'Skills' => 'array', // إذا كانت JSON
    ];

    /**
     * علاقة الوظيفة بالمستخدم المنشئ.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID'); // تأكد من المفتاح المحلي في User
    }

    /**
     * !!! علاقة الوظيفة بالشركة التي تنتمي إليها !!!
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'CompanyID', 'CompanyID'); // تأكد من المفتاح المحلي في Company
    }

    /**
     * علاقة الوظيفة بطلبات التقديم.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'JobID', 'JobID');
    }
}