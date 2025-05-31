<?php

use Illuminate\Support\Facades\Route;

// --- وحدات تحكم المصادقة ---
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
// use App\Http\Controllers\Auth\VerificationController;

// --- وحدات تحكم عامة ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\JobOpportunityController;
use App\Http\Controllers\TrainingCourseController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UpgradeRequestController;
use App\Http\Controllers\LocalizationController; // !!! تم إضافة هذا الاستيراد !!!

// --- وحدات تحكم الخريج ---
// ... (كما هي) ...
use App\Http\Controllers\Graduate\DashboardController as GraduateDashboardController;
use App\Http\Controllers\Graduate\ProfileController as GraduateProfileController;
use App\Http\Controllers\Graduate\JobApplicationController as GraduateJobApplicationController;
use App\Http\Controllers\Graduate\EnrollmentController as GraduateEnrollmentController;
use App\Http\Controllers\Graduate\RecommendationController as GraduateRecommendationController;


// --- وحدات تحكم مدير الشركة ---
// ... (كما هي) ...
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\JobApplicationViewerController as CompanyJobApplicationViewerController;
use App\Http\Controllers\Company\EnrollmentViewerController as CompanyEnrollmentViewerController;
use App\Http\Controllers\Company\CompanyRequestController as CompanyRequestController;
use App\Http\Controllers\Company\CompanyProfileController as CompanyProfileController;
use App\Http\Controllers\Company\ManagedJobOpportunityController as CompanyManagedJobOpportunityController;
use App\Http\Controllers\Company\ManagedTrainingCourseController as CompanyManagedTrainingCourseController;


// --- وحدات تحكم الاستشاري ---
// ... (كما هي) ...
use App\Http\Controllers\Consultant\DashboardController as ConsultantDashboardController;
use App\Http\Controllers\Consultant\ProfileController as ConsultantProfileController;
use App\Http\Controllers\Consultant\EnrollmentViewerController as ConsultantEnrollmentViewerController;
use App\Http\Controllers\Consultant\ArticleController as ConsultantArticleController;


// --- وحدات تحكم الأدمن ---
// ... (كما هي) ...
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\GroupController as AdminGroupController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\JobOpportunityController as AdminJobOpportunityController;
use App\Http\Controllers\Admin\TrainingCourseController as AdminTrainingCourseController;
use App\Http\Controllers\Admin\CompanyRequestApprovalController as AdminCompanyRequestApprovalController;
use App\Http\Controllers\Admin\UpgradeRequestController as AdminUpgradeRequestController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- المسارات العامة ---
Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/jobs', [JobOpportunityController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job_opportunity}', [JobOpportunityController::class, 'show'])->name('jobs.show');
Route::get('/courses', [TrainingCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{training_course}', [TrainingCourseController::class, 'show'])->name('courses.show');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');

// !!! مسار تبديل اللغة !!!
Route::get('language/{locale}', [LocalizationController::class, 'switch'])->name('language.switch');

// --- مسارات المصادقة ---
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// --- المسارات التي تتطلب تسجيل الدخول ---
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::post('/account/upgrade-request', [UpgradeRequestController::class, 'store'])->name('upgrade.request.store');

    // --- مسارات الخريج ---
    Route::prefix('graduate')->name('graduate.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [GraduateDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [GraduateProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [GraduateProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [GraduateProfileController::class, 'update'])->name('profile.update');
        Route::resource('applications', GraduateJobApplicationController::class)->only(['index', 'show', 'destroy']);
        Route::resource('enrollments', GraduateEnrollmentController::class)->only(['index', 'show', 'destroy']);
        Route::get('/recommendations', [GraduateRecommendationController::class, 'index'])->name('recommendations.index');
    });
    Route::post('/jobs/{job_opportunity}/apply', [GraduateJobApplicationController::class, 'store'])->name('jobs.apply');
    Route::post('/courses/{training_course}/enroll', [GraduateEnrollmentController::class, 'store'])->name('courses.enroll');


    // --- مسارات مدير الشركة ---
    Route::prefix('company-manager')->name('company-manager.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [CompanyProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [CompanyProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [CompanyProfileController::class, 'update'])->name('profile.update');
        Route::resource('job-opportunities', CompanyManagedJobOpportunityController::class);
        Route::resource('training-courses', CompanyManagedTrainingCourseController::class);
        Route::resource('job-applications', CompanyJobApplicationViewerController::class)->only(['index', 'show', 'update']);
        Route::resource('course-enrollments', CompanyEnrollmentViewerController::class)->only(['index', 'show', 'update']);
        Route::get('/request-company', [CompanyRequestController::class, 'create'])->name('request.create');
        Route::post('/request-company', [CompanyRequestController::class, 'store'])->name('request.store');
    });

    // --- مسارات الاستشاري ---
    Route::prefix('consultant')->name('consultant.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [ConsultantDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ConsultantProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ConsultantProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ConsultantProfileController::class, 'update'])->name('profile.update');
        Route::resource('articles', ConsultantArticleController::class);
    });


    // --- مسارات الأدمن ---
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
        Route::resource('skills', AdminSkillController::class);
        Route::resource('groups', AdminGroupController::class);
        Route::resource('companies', AdminCompanyController::class);
        Route::resource('articles', AdminArticleController::class);
        Route::resource('job-opportunities', AdminJobOpportunityController::class);
        Route::resource('training-courses', AdminTrainingCourseController::class);
        Route::resource('company-requests', AdminCompanyRequestApprovalController::class)
             ->parameters(['company-requests' => 'company'])
             ->only(['index', 'show', 'update', 'destroy']);
        Route::resource('upgrade-requests', AdminUpgradeRequestController::class)
             ->parameters(['upgrade-requests' => 'upgradeRequest'])
             ->only(['index', 'show', 'update', 'destroy']);
    });

});