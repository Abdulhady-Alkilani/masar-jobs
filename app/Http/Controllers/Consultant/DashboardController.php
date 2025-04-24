<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\TrainingCourse;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    /**
     * Apply middleware for authentication and consultant role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isConsultant'*/]);
    }

    /**
     * Show the consultant's dashboard.
     */
    public function index()
    {
        $consultant = Auth::user()->load('profile');

        // Fetch data relevant to this consultant
        $recentArticles = Article::where('UserID', $consultant->UserID)
            ->latest('Date')
            ->limit(5)
            ->get();

        $recentCourses = TrainingCourse::where('UserID', $consultant->UserID)
             ->latest()
             ->limit(5)
             ->get();

        // Count total enrollments in consultant's courses
        $totalEnrollmentsCount = Enrollment::whereIn('CourseID', function ($query) use ($consultant) {
             $query->select('CourseID')->from('training_courses')->where('UserID', $consultant->UserID);
         })->count();


        return view('consultant.dashboard', compact(
            'consultant',
            'recentArticles',
            'recentCourses',
            'totalEnrollmentsCount'
        ));
    }
}