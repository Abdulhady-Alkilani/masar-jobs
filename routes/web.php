<?php

use Illuminate\Support\Facades\Route;

// --- وحدات تحكم المصادقة ---
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;

// --- وحدات تحكم عامة ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\JobOpportunityController;
use App\Http\Controllers\TrainingCourseController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GroupController;

// --- وحدات تحكم الخريج ---
use App\Http\Controllers\Graduate\DashboardController as GraduateDashboardController;
use App\Http\Controllers\Graduate\ProfileController as GraduateProfileController;
use App\Http\Controllers\Graduate\JobApplicationController as GraduateJobApplicationController;
use App\Http\Controllers\Graduate\EnrollmentController as GraduateEnrollmentController;
use App\Http\Controllers\Graduate\RecommendationController as GraduateRecommendationController;

// --- وحدات تحكم مدير الشركة ---
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\JobApplicationViewerController as CompanyJobApplicationViewerController;
use App\Http\Controllers\Company\EnrollmentViewerController as CompanyEnrollmentViewerController;
use App\Http\Controllers\Company\CompanyRequestController as CompanyRequestController;

// --- وحدات تحكم الاستشاري ---
use App\Http\Controllers\Consultant\DashboardController as ConsultantDashboardController;
use App\Http\Controllers\Consultant\ProfileController as ConsultantProfileController;
// (سيتم استخدام ArticleController و TrainingCourseController العام مع التحقق من الصلاحيات)
use App\Http\Controllers\Consultant\EnrollmentViewerController as ConsultantEnrollmentViewerController;

// --- وحدات تحكم الأدمن ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\GroupController as AdminGroupController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\JobOpportunityController as AdminJobOpportunityController;
use App\Http\Controllers\Admin\TrainingCourseController as AdminTrainingCourseController;
use App\Http\Controllers\Admin\CompanyRequestApprovalController as AdminCompanyRequestApprovalController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- المسارات العامة (للزوار وغيرهم) ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// عرض المقالات والوظائف والدورات والشركات والمجموعات للعامة
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show'); // استخدام Route Model Binding

Route::get('/jobs', [JobOpportunityController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job_opportunity}', [JobOpportunityController::class, 'show'])->name('jobs.show');

Route::get('/courses', [TrainingCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{training_course}', [TrainingCourseController::class, 'show'])->name('courses.show');

Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');

Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');


// --- مسارات المصادقة ---
// (يمكن استخدام Auth::routes(); إذا لم تكن بحاجة لتخصيص)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
// Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');


// --- المسارات التي تتطلب تسجيل الدخول ---
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home'); // لوحة تحكم عامة أو توجيه حسب الدور

    // --- مسارات الخريج ---
    Route::prefix('graduate')->name('graduate.')->middleware(['auth'/*, 'isGraduate'*/])->group(function () {
        Route::get('/dashboard', [GraduateDashboardController::class, 'index'])->name('dashboard');
        Route::resource('profile', GraduateProfileController::class)->only(['show', 'edit', 'update']); // الخريج يدير ملفه
        Route::resource('applications', GraduateJobApplicationController::class)->only(['index', 'show', 'store', 'destroy']); // عرض وتقديم وإلغاء الطلبات
        Route::resource('enrollments', GraduateEnrollmentController::class)->only(['index', 'show', 'store', 'destroy']); // عرض وتسجيل وإلغاء التسجيل
        Route::get('/recommendations', [GraduateRecommendationController::class, 'index'])->name('recommendations');
        // التقديم على الوظيفة - قد يكون ضمن JobOpportunityController العام أو هنا
        Route::post('/jobs/{job_opportunity}/apply', [GraduateJobApplicationController::class, 'store'])->name('jobs.apply');
        // التسجيل في الدورة - قد يكون ضمن TrainingCourseController العام أو هنا
        Route::post('/courses/{training_course}/enroll', [GraduateEnrollmentController::class, 'store'])->name('courses.enroll');

    });

    // --- مسارات مدير الشركة ---
    Route::prefix('company-manager')->name('company-manager.')->middleware(['auth'/*, 'isCompanyManager'*/])->group(function () {
        Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
        Route::resource('company-profile', CompanyController::class)->only(['show', 'edit', 'update']); // إدارة ملف الشركة الخاص به
        Route::resource('job-opportunities', JobOpportunityController::class); // إدارة فرص العمل الخاصة به
        Route::resource('training-courses', TrainingCourseController::class); // إدارة الدورات الخاصة به (إذا مسموح)
        Route::resource('job-applications', CompanyJobApplicationViewerController::class)->only(['index', 'show']); // عرض طلبات التوظيف لفرصه
        Route::resource('course-enrollments', CompanyEnrollmentViewerController::class)->only(['index', 'show']); // عرض المسجلين في دوراته
        // Route::get('/request-company', [CompanyRequestController::class, 'create'])->name('request.create'); // إذا كان الطلب منفصل
        // Route::post('/request-company', [CompanyRequestController::class, 'store'])->name('request.store');
    });

    // --- مسارات الاستشاري ---
    Route::prefix('consultant')->name('consultant.')->middleware(['auth'/*, 'isConsultant'*/])->group(function () {
        Route::get('/dashboard', [ConsultantDashboardController::class, 'index'])->name('dashboard');
        Route::resource('profile', ConsultantProfileController::class)->only(['show', 'edit', 'update']); // إدارة ملفه الشخصي
        Route::resource('articles', ArticleController::class); // إدارة مقالاته
        Route::resource('training-courses', TrainingCourseController::class); // إدارة الدورات الخاصة به (إذا مسموح)
        Route::resource('course-enrollments', ConsultantEnrollmentViewerController::class)->only(['index', 'show']);// عرض المسجلين في دوراته (إذا أنشأها)
    });


    // --- مسارات الأدمن ---
    Route::prefix('admin')->name('admin.')->middleware(['auth'/*, 'isAdmin'*/])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
        Route::resource('skills', AdminSkillController::class);
        Route::resource('groups', AdminGroupController::class);
        Route::resource('companies', AdminCompanyController::class); // إدارة جميع الشركات
        Route::resource('articles', AdminArticleController::class); // إدارة جميع المقالات
        Route::resource('job-opportunities', AdminJobOpportunityController::class); // إدارة جميع الفرص
        Route::resource('training-courses', AdminTrainingCourseController::class); // إدارة جميع الدورات
        Route::resource('company-requests', AdminCompanyRequestApprovalController::class)->only(['index', 'show', 'update', 'destroy']); // الموافقة/الرفض
        // مسارات إضافية للأدمن مثل الإحصائيات، الإعدادات، إلخ.
    });

});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
