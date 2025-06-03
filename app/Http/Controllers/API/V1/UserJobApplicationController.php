<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserJobApplicationController extends Controller
{
    /**
     * Display a listing of the authenticated user's job applications.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $applications = $user->jobApplications()
                             ->with('jobOpportunity:JobID,Job Title,Type') // Eager load basic job info
                             ->latest('Date') // Order by application date
                             ->get();

        // Consider using JobApplicationResourceCollection
        return response()->json($applications);
    }

    /**
     * Store a new job application for the authenticated user.
     * Route: POST /jobs/{job_opportunity}/apply
     */
    public function store(Request $request, JobOpportunity $job_opportunity) // Route model binding
    {
        $user = $request->user();

        // Check if user already applied
        $existingApplication = JobApplication::where('UserID', $user->UserID)
                                             ->where('JobID', $job_opportunity->JobID)
                                             ->first();

        if ($existingApplication) {
            return response()->json(['message' => 'You have already applied for this job.'], 409); // Conflict
        }

        // Check if job is still active/accepting applications (optional)
        if ($job_opportunity->Status !== 'مفعل' || ($job_opportunity->{'End Date'} && Carbon::parse($job_opportunity->{'End Date'})->isPast())) {
             return response()->json(['message' => 'This job opportunity is no longer accepting applications.'], 400);
        }

        $validatedData = $request->validate([
            'Description' => 'nullable|string',
            'CV' => 'nullable|string|max:255', // Or handle file upload
        ]);

        $application = JobApplication::create([
            'UserID' => $user->UserID,
            'JobID' => $job_opportunity->JobID,
            'Status' => 'Pending', // Initial status
            'Date' => Carbon::now(),
            'Description' => $validatedData['Description'] ?? null,
            'CV' => $validatedData['CV'] ?? null, // Store path after upload
        ]);

        // Consider using JobApplicationResource
        return response()->json($application, 201);
    }

    /**
     * Remove the specified job application of the authenticated user.
     * Route: DELETE /my-applications/{job_application}
     */
    public function destroy(Request $request, JobApplication $job_application) // Route model binding
    {
        $user = $request->user();

        // Authorization: Check if the application belongs to the authenticated user
        if ($job_application->UserID !== $user->UserID) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Optional: Check if application can be cancelled (e.g., only if status is 'Pending')
        // if ($job_application->Status !== 'Pending') {
        //     return response()->json(['message' => 'Cannot cancel application with status: ' . $job_application->Status], 400);
        // }

        $job_application->delete();

        return response()->json(null, 204);
    }
}