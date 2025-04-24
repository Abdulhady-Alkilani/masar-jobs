<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobOpportunity;
use App\Models\JobApplication;
use App\Models\TrainingCourse;
use App\Models\Enrollment;

class DashboardController extends Controller
{
     /**
     * Apply middleware for authentication and company manager role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isCompanyManager'*/]);
    }

    /**
     * Show the company manager's dashboard.
     */
    public function index()
    {
        $manager = Auth::user()->load('company'); // Load the company managed by this user

        if (!$manager->company) {
            // Redirect to company creation/request page or show an error/info message
            return redirect()->route('company-manager.request.create') // Example route
                             ->with('warning', 'Please complete your company profile setup.');
        }

        $company = $manager->company;

        // Fetch data relevant to this company manager
        $recentJobs = JobOpportunity::where('UserID', $manager->UserID) // Assuming manager UserID is used
            ->latest('Date')
            ->limit(5)
            ->get();

        // Count new applications for the manager's jobs
        $newApplicationsCount = JobApplication::whereIn('JobID', function ($query) use ($manager) {
            $query->select('JobID')->from('job_opportunities')->where('UserID', $manager->UserID);
        })
        ->where('Status', 'Pending') // Count only new/pending applications
        ->count();

        // Fetch recent company courses (if applicable)
        $recentCourses = TrainingCourse::where('UserID', $manager->UserID)
            ->latest()
            ->limit(5)
            ->get();

        // Count active enrollments in company's courses
        $activeEnrollmentsCount = Enrollment::whereIn('CourseID', function ($query) use ($manager) {
             $query->select('CourseID')->from('training_courses')->where('UserID', $manager->UserID);
         })
         ->where('Status', 'قيد التقدم')
         ->count();


        return view('company_manager.dashboard', compact(
            'manager',
            'company',
            'recentJobs',
            'newApplicationsCount',
            'recentCourses',
            'activeEnrollmentsCount'
        ));
    }
}