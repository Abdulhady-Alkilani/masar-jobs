<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobOpportunity;
use App\Models\TrainingCourse;
use App\Models\User;

class RecommendationController extends Controller
{
    /**
     * Provide recommendations (jobs, courses) for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user()->load(['skills', 'profile']); // Load skills and profile

        // --- Recommendation Logic ---
        // This is a placeholder. Real logic can be complex.

        // 1. Get User Skills (IDs or Names)
        $userSkillNames = $user->skills->pluck('Name')->toArray();
        $userSkillIDs = $user->skills->pluck('SkillID')->toArray();

        // 2. Simple Job Recommendation: Find jobs where the 'Skills' text column contains any of the user's skill names
        $recommendedJobs = JobOpportunity::where('Status', 'مفعل')
            ->where(function ($query) use ($userSkillNames) {
                foreach ($userSkillNames as $skillName) {
                    // Case-insensitive search within the text field
                    $query->orWhereRaw('LOWER(`Skills`) LIKE ?', ['%' . strtolower($skillName) . '%']);
                }
            })
            // Exclude jobs the user already applied for
            ->whereDoesntHave('jobApplications', function ($q) use ($user) {
                $q->where('UserID', $user->UserID);
            })
            ->with('user:UserID,first_name,last_name') // Include job poster info
            ->take(10) // Limit results
            ->get();


        // 3. Simple Course Recommendation: Find courses (Needs a 'Skills' field in TrainingCourse model/table or link to skills table)
        // Assuming TrainingCourse model had a 'required_skills' text field for simplicity:
        /*
        $recommendedCourses = TrainingCourse::where(function ($query) use ($userSkillNames) {
                foreach ($userSkillNames as $skillName) {
                    $query->orWhereRaw('LOWER(`required_skills`) LIKE ?', ['%' . strtolower($skillName) . '%']);
                }
            })
             // Exclude courses the user is already enrolled in
            ->whereDoesntHave('enrollments', function ($q) use ($user) {
                $q->where('UserID', $user->UserID);
            })
            ->with('creator:UserID,first_name,last_name') // Include course creator info
            ->take(10)
            ->get();
        */
         // If no 'required_skills' field, you might recommend based on user's field/university or popular courses.
         // Placeholder: Recommend latest courses not enrolled in
         $recommendedCourses = TrainingCourse::whereDoesntHave('enrollments', function ($q) use ($user) {
                $q->where('UserID', $user->UserID);
            })
            ->with('creator:UserID,first_name,last_name')
            ->latest()
            ->take(10)
            ->get();


        // 4. TODO: Implement AI Recommendation (e.g., OpenAI API)
        // - Send user profile data (skills, descriptions, university, GPA) to OpenAI API.
        // - Ask the AI to recommend job titles or course topics based on the profile.
        // - Query your database for jobs/courses matching the AI suggestions.

        // Consider using API Resources for jobs and courses
        return response()->json([
            'recommended_jobs' => $recommendedJobs,
            'recommended_courses' => $recommendedCourses,
            // 'ai_suggestions' => [] // Add AI results here later
        ]);
    }
}