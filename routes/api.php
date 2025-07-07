<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- وحدات تحكم API العامة (في مجلد API/V1) ---
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ArticleController as ApiArticleController;
use App\Http\Controllers\API\V1\JobOpportunityController as ApiJobOpportunityController;
use App\Http\Controllers\API\V1\TrainingCourseController as ApiTrainingCourseController;
use App\Http\Controllers\API\V1\CompanyController as ApiCompanyController;
use App\Http\Controllers\API\V1\GroupController as ApiGroupController;
use App\Http\Controllers\API\V1\SkillController as ApiSkillController;
use App\Http\Controllers\API\V1\ProfileController as ApiProfileController;
use App\Http\Controllers\API\V1\UserJobApplicationController as ApiUserJobApplicationController;
use App\Http\Controllers\API\V1\UserEnrollmentController as ApiUserEnrollmentController;
use App\Http\Controllers\API\V1\RecommendationController as ApiRecommendationController;
use App\Http\Controllers\API\V1\UserNotificationController;
use App\Http\Controllers\API\V1\UserCompanyRequestController;
// --- وحدات تحكم مدير الشركة (في مجلد API/V1/Company) ---
use App\Http\Controllers\API\V1\Company\ManagedJobOpportunityController as ApiManagedJobOpportunityController;
use App\Http\Controllers\API\V1\Company\ManagedTrainingCourseController as ApiManagedTrainingCourseController;
use App\Http\Controllers\API\V1\Company\ApplicantController as ApiApplicantController;
use App\Http\Controllers\API\V1\Company\EnrolleeController as ApiEnrolleeController;
use App\Http\Controllers\API\V1\Company\CompanyCreationRequestController; // <-- وحدة تحكم طلب إنشاء الشركة

// --- وحدات تحكم الاستشاري (في مجلد API/V1/Consultant) ---
use App\Http\Controllers\API\V1\Consultant\ManagedArticleController as ApiManagedArticleController;

// --- وحدات تحكم الأدمن (في مجلد API/V1/Admin) ---
use App\Http\Controllers\API\V1\Admin\UserController as ApiAdminUserController;
use App\Http\Controllers\API\V1\Admin\ApiAdminCompanyRequestController; // <-- إدارة طلبات الشركة المعلقة


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "api" middleware group. Make something great!
|
*/

// --- مسارات API العامة (لا تحتاج مصادقة) ---
Route::prefix('v1')->group(function () {

    // مصادقة API
    Route::post('/register', [AuthController::class, 'register']); // المستخدم يسجل بأنواع متعددة
    Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']); // TODO: Implement

    // استعراض الموارد العامة (للعرض فقط، يجب تصفية بالحالة داخل Controllers للعرض غير الإداري)
    Route::apiResource('articles', ApiArticleController::class)->only(['index', 'show']);
    Route::apiResource('jobs', ApiJobOpportunityController::class)->only(['index', 'show']);
    Route::apiResource('courses', ApiTrainingCourseController::class)->only(['index', 'show']);
    Route::apiResource('companies', ApiCompanyController::class)->only(['index', 'show']); // يعرض الشركات الموافق عليها للعامة
    Route::apiResource('skills', ApiSkillController::class)->only(['index']); // عرض المهارات للبحث
    Route::apiResource('groups', ApiGroupController::class)->only(['index', 'show']); // عرض المجموعات

});

// --- مسارات API المحمية (تتطلب مصادقة توكن Sanctum) ---
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // مصادقة API (تسجيل الخروج)
    Route::post('/logout', [AuthController::class, 'logout']);
    // جلب بيانات المستخدم الحالي وكافة علاقاته المهمة (لأي مستخدم مسجل دخول)
    Route::get('/user', function (Request $request) {
        // حمل العلاقات التي تحتاجها الواجهة الأمامية للمستخدم الحالي بشكل متكرر
        return $request->user()->load(['profile.skills', 'company', 'createdTrainingCourses']);
    });

    // --- مسارات المستخدم الحالي (لأي مستخدم مسجل دخول) ---
    // إدارة بيانات ملفه الشخصي الأساسية ومهاراته
    Route::get('/profile', [ApiProfileController::class, 'show']); // عرض بيانات المستخدم وملفه وعلاقاته
    Route::put('/profile', [ApiProfileController::class, 'update']); // تحديث بيانات المستخدم الأساسية وملفه
    Route::post('/profile/skills', [ApiProfileController::class, 'syncSkills']); // مزامنة مهارات المستخدم

    // إدارة طلبات التقديم على الوظائف الخاصة بالمستخدم
    Route::get('/my-applications', [ApiUserJobApplicationController::class, 'index']);
    Route::post('/jobs/{job_opportunity}/apply', [ApiUserJobApplicationController::class, 'store']);
    Route::delete('/my-applications/{job_application}', [ApiUserJobApplicationController::class, 'destroy']);

    // إدارة تسجيلات الدورات التدريبية الخاصة بالمستخدم
    Route::get('/my-enrollments', [ApiUserEnrollmentController::class, 'index']);
    Route::post('/courses/{training_course}/enroll', [ApiUserEnrollmentController::class, 'store']);
    Route::delete('/my-enrollments/{enrollment}', [ApiUserEnrollmentController::class, 'destroy']);


    Route::post('/company-requests', [UserCompanyRequestController::class, 'store']); // <--- إضافة هذا الراوت



    // توصيات المستخدم
    Route::get('/recommendations', [ApiRecommendationController::class, 'index']);

    // إدارة الإشعارات الخاصة بالمستخدم
    Route::get('/notifications', [UserNotificationController::class, 'index']);
    Route::put('/notifications/{notification}', [UserNotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [UserNotificationController::class, 'destroy']);
    Route::post('/notifications/mark-all-as-read', [UserNotificationController::class, 'markAllAsRead']);


    // --- مسارات مدير الشركة (تتطلب صلاحية مدير شركة) ---
    // هذه المجموعة تتطلب middleware 'isCompanyManager' للوصول إليها
    Route::prefix('company-manager')->middleware(['auth:sanctum', 'isCompanyManager'])->group(function () { // TODO: Implement isCompanyManager Middleware
        // عرض وتحديث الشركة المرتبطة بمدير الشركة الحالي (الشركة التي تم ربطه بها بعد موافقة الأدمن)
        Route::get('/company', [ApiCompanyController::class, 'showManagedCompany']);
        Route::put('/company', [ApiCompanyController::class, 'updateManagedCompany']);

        // مسار لمدير الشركة لتقديم طلب انشاء شركة (إذا لم يتم ربطه بشركة بعد)
        // الكنترولر داخله يجب أن يتحقق أن المدير لم يطلب شركة أو لم يتم ربطه بواحدة مسبقاً
        Route::post('/company-request', [CompanyCreationRequestController::class, 'store']); // <--- مسار تقديم طلب إنشاء الشركة

        // إدارة فرص العمل الخاصة بالشركة التي يديرها
        Route::apiResource('jobs', ApiManagedJobOpportunityController::class);

        // إدارة دورات الشركة التي يديرها (إذا مسموح بهذا الدور)
        Route::apiResource('courses', ApiManagedTrainingCourseController::class); // TODO: Check if Company Manager can manage courses

        // عرض المتقدمين لوظائف شركته
        Route::get('/jobs/{job_opportunity}/applicants', [ApiApplicantController::class, 'index']);

        // عرض المسجلين بدورات شركته
        Route::get('/courses/{training_course}/enrollees', [ApiEnrolleeController::class, 'index']); // TODO: Check if Company Manager can see enrollees
    });


    // --- مسارات الاستشاري (تتطلب صلاحية استشاري) ---
    // هذه المجموعة تتطلب middleware 'isConsultant' للوصول إليها
    Route::prefix('consultant')->middleware(['auth:sanctum', 'isConsultant'])->group(function () { // TODO: Implement isConsultant Middleware
        // إدارة مقالات الاستشاري الخاصة به
        Route::apiResource('articles', ApiManagedArticleController::class);

        // إذا كان الاستشاري ينشر دورات
        // Route::apiResource('courses', ManagedTrainingCourseController::class); // TODO: Check if Consultant can manage courses
        // Route::get('/courses/{training_course}/enrollees', [EnrolleeController::class, 'index']); // TODO: Check if Consultant can see enrollees
    });


    // --- مسارات الأدمن (تتطلب صلاحية أدمن) ---
    // هذه المجموعة تتطلب middleware 'isAdmin' للوصول إليها
    Route::prefix('admin')->middleware(['auth:sanctum', 'isAdmin'])->group(function () { // TODO: Implement isAdmin Middleware
        // إدارة شاملة لجميع المستخدمين
        Route::apiResource('users', ApiAdminUserController::class);

        // إدارة شاملة لجميع المهارات
        Route::apiResource('skills', ApiSkillController::class);

        // إدارة شاملة لجميع المجموعات
        Route::apiResource('groups', ApiGroupController::class);

        // إدارة شاملة لجميع الشركات (عرض الكل، إنشاء مباشر، تحديث الكل، حذف الكل)
        // هذه المسارات تستخدم للتحكم في سجلات الشركات بغض النظر عن حالتها
        Route::apiResource('companies', ApiCompanyController::class);


        Route::apiResource('articles', ApiArticleController::class); // إدارة جميع المقالات
        Route::apiResource('jobs', ApiJobOpportunityController::class); // إدارة جميع فرص العمل
        Route::apiResource('courses', ApiTrainingCourseController::class); // إدارة جميع الدورات

        // إدارة طلبات انشاء الشركات المعلقة بواسطة الأدمن
        // هذه المسارات تستخدم لإدارة سير عمل الموافقة/الرفض للشركات التي حالتها 'pending'
        Route::prefix('company-requests')->group(function () {
             Route::get('/', [ApiAdminCompanyRequestController::class, 'index']); // عرض الطلبات المعلقة (شركات بحالة pending)
             Route::put('/{company}/approve', [ApiAdminCompanyRequestController::class, 'approve']); // الموافقة على طلب شركة (تغيير حالة الشركة ونقل الملف)
             Route::put('/{company}/reject', [ApiAdminCompanyRequestController::class, 'reject']); // رفض طلب شركة (حذف سجل الشركة والملف المؤقت)
        });

        // ... (Admin routes for articles, jobs, courses) ...
    });
});

// TODO: Implement the middleware classes: isAdmin, isCompanyManager, isConsultant
//       Apply them to the route groups above.
// TODO: Implement Notification sending (Admin notification on new request, User notification on approval/rejection)
// TODO: Consider using API Resources for better response formatting.