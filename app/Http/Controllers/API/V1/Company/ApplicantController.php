<?php

namespace App\Http\Controllers\API\V1\Company; // Correct Namespace

use App\Http\Controllers\Controller;
use App\Models\JobOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    /**
     * Display a listing of applicants for a specific job opportunity managed by the company manager.
     * Route: GET /company-manager/jobs/{job_opportunity}/applicants
     */
    public function index(Request $request, JobOpportunity $job_opportunity) // Route model binding
    {
        $user = $request->user();

        // Authorization: Check if the job belongs to the manager
        if ($job_opportunity->UserID !== $user->UserID || $user->type !== 'مدير شركة') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Eager load applicant user details and their profile
        $applicants = $job_opportunity->jobApplications()
                                      ->with(['user' => function ($query) {
                                          $query->select('UserID', 'first_name', 'last_name', 'email', 'phone') // Select specific user fields
                                                ->with('profile'); // Load the user's profile
                                      }])
                                      ->orderBy('Date') // Order by application date
                                      ->get();

        // Consider using a combined ApplicantResource or JobApplicationResource collection
        return response()->json($applicants);

        // You might want to transform the response to show user/profile data more directly:
        /*
        $applicantData = $applicants->map(function ($application) {
            return [
                'application_id' => $application->ID,
                'application_status' => $application->Status,
                'application_date' => $application->Date,
                'application_description' => $application->Description,
                'application_cv' => $application->CV,
                'applicant_user_id' => $application->user->UserID,
                'applicant_name' => $application->user->first_name . ' ' . $application->user->last_name,
                'applicant_email' => $application->user->email,
                'applicant_phone' => $application->user->phone,
                'applicant_profile' => $application->user->profile // The whole profile object
            ];
        });
        return response()->json($applicantData);
        */
    }

    // TODO: Add methods to change applicant status (e.g., PUT /company-manager/applications/{application}/status)
}