<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;
use App\Models\Enrollment;

class DashboardController extends Controller
{
     /**
     * Create a new controller instance.
     * Apply middleware to ensure user is authenticated and is a graduate.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isGraduate'*/]); // Apply role middleware in routes/web.php
    }

    /**
     * Show the graduate's application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch relevant data for the dashboard
        $recentApplications = JobApplication::where('UserID', $user->UserID)
            ->with('jobOpportunity') // Load job details
            ->latest('Date') // Or latest created_at
            ->limit(5)
            ->get();

        $activeEnrollments = Enrollment::where('UserID', $user->UserID)
            ->where('Status', 'قيد التقدم') // Example: Show active courses
            ->with('trainingCourse') // Load course details
            ->latest('Date')
            ->limit(5)
            ->get();

        // You might also fetch profile completion status, recommendations etc.
        $profile = $user->profile; // Assuming relationship is set up

        return view('graduate.dashboard', compact(
            'user',
            'recentApplications',
            'activeEnrollments',
            'profile'
        ));
    }
}