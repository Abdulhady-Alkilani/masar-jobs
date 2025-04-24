<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobOpportunity;
use App\Models\TrainingCourse;
use App\Models\User;

class RecommendationController extends Controller
{
     /**
     * Apply middleware for authentication and graduate role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isGraduate'*/]);
    }

    /**
     * Display job and course recommendations for the graduate.
     */
    public function index()
    {
        $user = Auth::user()->load(['profile', 'skills']);

        // --- Recommendation Logic (Example - Needs Refinement) ---
        $userSkills = $user->skills->pluck('Name')->toArray(); // Get skill names

        // 1. Job Recommendations based on skills (simple text matching)
        $recommendedJobsQuery = JobOpportunity::query()
            ->where('Status', 'مفعل')
            ->where(function ($query) use ($userSkills) {
                foreach ($userSkills as $skill) {
                    // Search for skill name within the job's skills text column
                    $query->orWhere('Skills', 'like', "%{$skill}%");
                }
                 // Also consider job title matching?
                 // $query->orWhere('Job Title', 'like', "%keyword_from_profile%");
            })
            ->whereDoesntHave('jobApplications', function ($query) use ($user) { // Exclude jobs already applied for
                $query->where('UserID', $user->UserID);
            });

        // Add location preference from profile? GPA requirement?
        // if($user->profile?->preferred_location) { ... }

        $recommendedJobs = $recommendedJobsQuery->inRandomOrder()->limit(5)->get();


        // 2. Course Recommendations (Example: based on missing skills or desired level)
        // This is more complex. Could suggest courses for skills the user *doesn't* have
        // or courses matching their skill level (beginner, intermediate).
        // For simplicity, let's just show some recent courses they aren't enrolled in.

        $recommendedCoursesQuery = TrainingCourse::query()
             // ->where('Stage', $user->profile?->desired_level) // If user specifies desired level
             ->whereDoesntHave('enrollments', function ($query) use ($user) { // Exclude courses already enrolled in
                 $query->where('UserID', $user->UserID);
             });

        $recommendedCourses = $recommendedCoursesQuery->latest()->limit(5)->get();


        // 3. AI Recommendations (Placeholder)
        $aiRecommendations = [];
        // if (config('services.openai.key')) {
        //     try {
        //         // Prepare prompt based on user profile, skills, application history
        //         // Call OpenAI API
        //         // Parse response
        //     } catch (\Exception $e) {
        //         // Log error
        //     }
        // }


        return view('graduate.recommendations.index', compact(
            'user',
            'recommendedJobs',
            'recommendedCourses',
            'aiRecommendations'
        ));
    }
}