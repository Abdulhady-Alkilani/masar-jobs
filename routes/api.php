<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- وحدات تحكم API (قد تحتاج لإنشائها أو استخدام الحالية مع تعديل لتناسب API) ---

// --- وحدات تحكم المصادقة (API) ---
// (استخدم Sanctum أو Passport للتعامل مع التوكن)
// مثال باستخدام وحدات تحكم مخصصة لـ API
use App\Http\Controllers\API\V1\AuthController;

// --- وحدات تحكم الموارد (API) ---
use App\Http\Controllers\API\V1\ArticleController as ApiArticleController;
use App\Http\Controllers\API\V1\JobOpportunityController as ApiJobOpportunityController;
use App\Http\Controllers\API\V1\TrainingCourseController as ApiTrainingCourseController;
use App\Http\Controllers\API\V1\CompanyController as ApiCompanyController;
use App\Http\Controllers\API\V1\GroupController as ApiGroupController;
use App\Http\Controllers\API\V1\SkillController as ApiSkillController; // لإدارة المهارات (للأدمن) أو البحث عنها

// --- وحدات تحكم المستخدم الحالي (API) ---
use App\Http\Controllers\API\V1\ProfileController as ApiProfileController;
use App\Http\Controllers\API\V1\UserJobApplicationController as ApiUserJobApplicationController;
use App\Http\Controllers\API\V1\UserEnrollmentController as ApiUserEnrollmentController;
use App\Http\Controllers\API\V1\RecommendationController as ApiRecommendationController; // للحصول على التوصيات

// --- وحدات تحكم مدير الشركة (API) ---
use App\Http\Controllers\API\V1\Company\ManagedJobOpportunityController as ApiManagedJobOpportunityController;
use App\Http\Controllers\API\V1\Company\ManagedTrainingCourseController as ApiManagedTrainingCourseController;
use App\Http\Controllers\API\V1\Company\ApplicantController as ApiApplicantController;
use App\Http\Controllers\API\V1\Company\EnrolleeController as ApiEnrolleeController;

// --- وحدات تحكم الاستشاري (API) ---
use App\Http\Controllers\API\V1\Consultant\ManagedArticleController as ApiManagedArticleController;
// (قد يستخدم ManagedTrainingCourseController إذا كان مسموحاً)

// --- وحدات تحكم الأدمن (API) ---
use App\Http\Controllers\API\V1\Admin\UserController as ApiAdminUserController;
use App\Http\Controllers\API\V1\Admin\CompanyRequestController as ApiAdminCompanyRequestController;
// (الأدمن قد يستخدم نفس وحدات التحكم العامة ولكن مع صلاحيات كاملة تتحقق داخلها)


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// --- مسارات API العامة (لا تحتاج مصادقة) ---
Route::prefix('v1')->group(function () {

    // مصادقة API
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']); // (تحتاج لتنفيذ)

    // استعراض الموارد العامة
    Route::apiResource('articles', ApiArticleController::class)->only(['index', 'show']);
    Route::apiResource('jobs', ApiJobOpportunityController::class)->only(['index', 'show']);
    Route::apiResource('courses', ApiTrainingCourseController::class)->only(['index', 'show']);
    Route::apiResource('companies', ApiCompanyController::class)->only(['index', 'show']);
    Route::apiResource('skills', ApiSkillController::class)->only(['index']); // بحث عن مهارات

});

// --- مسارات API المحمية (تتطلب مصادقة توكن Sanctum) ---
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    Route::apiResource('groups', ApiGroupController::class)->only(['index', 'show']);

    // مصادقة API (تسجيل الخروج)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) { // الحصول على بيانات المستخدم الحالي
        return $request->user()->load(['profile', 'skills']); // مثال لتحميل العلاقات
    });

    // --- مسارات المستخدم الحالي (خريج، استشاري، مدير) ---
    Route::get('/profile', [ApiProfileController::class, 'show']); // عرض الملف الشخصي للمستخدم الحالي
    Route::put('/profile', [ApiProfileController::class, 'update']); // تحديث الملف الشخصي للمستخدم الحالي
    Route::post('/profile/skills', [ApiProfileController::class, 'syncSkills']); // تحديث مهارات المستخدم الحالي

    // طلبات التوظيف الخاصة بالمستخدم
    Route::get('/my-applications', [ApiUserJobApplicationController::class, 'index']);
    Route::post('/jobs/{job_opportunity}/apply', [ApiUserJobApplicationController::class, 'store']); // تقديم طلب
    Route::delete('/my-applications/{job_application}', [ApiUserJobApplicationController::class, 'destroy']); // إلغاء طلب

    // التسجيل في الدورات الخاصة بالمستخدم
    Route::get('/my-enrollments', [ApiUserEnrollmentController::class, 'index']);
    Route::post('/courses/{training_course}/enroll', [ApiUserEnrollmentController::class, 'store']); // تسجيل في دورة
    Route::delete('/my-enrollments/{enrollment}', [ApiUserEnrollmentController::class, 'destroy']); // إلغاء تسجيل

    // توصيات المستخدم
    Route::get('/recommendations', [ApiRecommendationController::class, 'index']);

    // --- مسارات مدير الشركة ---
    Route::prefix('company-manager')->middleware(['auth:sanctum'/*, 'isCompanyManager'*/])->group(function () {
        Route::get('/company', [ApiCompanyController::class, 'showManagedCompany']); // عرض تفاصيل شركة المدير الحالية
        Route::put('/company', [ApiCompanyController::class, 'updateManagedCompany']); // تحديث شركة المدير الحالية
        Route::apiResource('jobs', ApiManagedJobOpportunityController::class); // إدارة فرص العمل الخاصة بالشركة
        Route::apiResource('courses', ApiManagedTrainingCourseController::class); // إدارة دورات الشركة (إذا مسموح)
        Route::get('/jobs/{job_opportunity}/applicants', [ApiApplicantController::class, 'index']); // عرض المتقدمين لوظيفة معينة
        Route::get('/courses/{training_course}/enrollees', [ApiEnrolleeController::class, 'index']); // عرض المسجلين بدورة معينة
    });

    // --- مسارات الاستشاري ---
    Route::prefix('consultant')->middleware(['auth:sanctum'/*, 'isConsultant'*/])->group(function () {
        Route::apiResource('articles', ApiManagedArticleController::class); // إدارة مقالات الاستشاري
        // Route::apiResource('courses', ApiManagedTrainingCourseController::class); // إذا كان الاستشاري ينشر دورات
        // Route::get('/courses/{training_course}/enrollees', [ApiEnrolleeController::class, 'index']); // إذا كان الاستشاري ينشر دورات
    });

    // --- مسارات الأدمن ---
    Route::prefix('admin')->middleware(['auth:sanctum'/*, 'isAdmin'*/])->group(function () {
        Route::apiResource('users', ApiAdminUserController::class);
        Route::apiResource('skills', ApiSkillController::class); // إدارة المهارات (إضافة/تعديل/حذف)
        Route::apiResource('groups', ApiGroupController::class); // إدارة المجموعات
        Route::apiResource('companies', ApiCompanyController::class); // إدارة جميع الشركات
        Route::apiResource('articles', ApiArticleController::class); // إدارة جميع المقالات
        Route::apiResource('jobs', ApiJobOpportunityController::class); // إدارة جميع فرص العمل
        Route::apiResource('courses', ApiTrainingCourseController::class); // إدارة جميع الدورات
        Route::get('company-requests', [ApiAdminCompanyRequestController::class, 'index']); // عرض طلبات إنشاء الشركات
        Route::put('company-requests/{company}/approve', [ApiAdminCompanyRequestController::class, 'approve']); // الموافقة
        Route::put('company-requests/{company}/reject', [ApiAdminCompanyRequestController::class, 'reject']); // الرفض
        // مسارات إضافية للإحصائيات والإعدادات
    });

});