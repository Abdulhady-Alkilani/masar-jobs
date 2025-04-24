<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\JobOpportunity;
use App\Models\TrainingCourse;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
     /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isAdmin'*/]);
    }

    /**
     * Show the admin dashboard with statistics.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'graduate_users' => User::where('type', 'خريج')->count(),
            'consultant_users' => User::where('type', 'خبير استشاري')->count(),
            'manager_users' => User::where('type', 'مدير شركة')->count(),
            'total_companies' => Company::count(),
            // 'pending_companies' => Company::where('Status', 'Pending')->count(), // If using status
            'total_jobs' => JobOpportunity::count(),
            'active_jobs' => JobOpportunity::where('Status', 'مفعل')->count(),
            'total_courses' => TrainingCourse::count(),
            'total_articles' => Article::count(),
            // Add more stats as needed
        ];

        // Fetch recent activities maybe?
        $recentUsers = User::latest()->limit(5)->get();
        // $pendingCompanies = Company::where('Status', 'Pending')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'/*, 'pendingCompanies'*/));
    }
}