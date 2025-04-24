<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOpportunity; // Needed? Maybe just for filtering
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationViewerController extends Controller
{
     /**
     * Apply middleware for authentication and company manager role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isCompanyManager'*/]);
    }

    /**
     * Display a listing of job applications for the manager's company.
     */
    public function index(Request $request)
    {
        $manager = Auth::user();

        // Get Job IDs posted by this manager
        $managerJobIds = JobOpportunity::where('UserID', $manager->UserID)->pluck('JobID');

        // Query applications for those jobs
        $query = JobApplication::whereIn('JobID', $managerJobIds)
                                ->with(['user', 'jobOpportunity']); // Load applicant and job details

        // Filtering Example: By Job
        if ($request->filled('job_id')) {
             $query->where('JobID', $request->input('job_id'));
        }

        // Filtering Example: By Status
        if ($request->filled('status')) {
             $query->where('Status', $request->input('status'));
        }

        $applications = $query->latest('Date')->paginate(20);

        // Get list of manager's jobs for the filter dropdown
        $managerJobs = JobOpportunity::where('UserID', $manager->UserID)
                                     ->orderBy('Job Title')
                                     ->get(['JobID', 'Job Title']);

        return view('company_manager.applications.index', compact('applications', 'managerJobs'));
    }


    /**
     * Display the specified job application.
     */
    public function show(JobApplication $application) // Route Model Binding simplifies this
    {
        // TODO: Add Authorization - Ensure the job application belongs to a job posted by this manager
        $manager = Auth::user();
        $job = $application->jobOpportunity; // Get the related job

        if (!$job || $job->UserID !== $manager->UserID) {
            abort(403, 'You do not have permission to view this application.');
        }

        $application->load(['user.profile', 'jobOpportunity']); // Load details
        return view('company_manager.applications.show', compact('application'));

        // NOTE: Need a way to securely view/download the CV (Storage::download?)
        // Maybe add a dedicated route/method for CV download that checks permission.
    }

    /**
     * Update the specified resource in storage (e.g., change application status).
     */
    public function update(Request $request, JobApplication $application)
    {
        // TODO: Add Authorization - Ensure manager owns the related job
        $manager = Auth::user();
        $job = $application->jobOpportunity;

        if (!$job || $job->UserID !== $manager->UserID) {
            abort(403, 'You do not have permission to update this application.');
        }

        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'Status' => 'required|string|in:Pending,Reviewed,Shortlisted,Rejected,Hired', // Define statuses
        ]);

        $application->update(['Status' => $validatedData['Status']]);

        // Optionally notify the applicant about the status change

        return redirect()->route('company-manager.job-applications.show', $application)
                         ->with('success', 'Application status updated.');
    }

    // Typically Managers don't create/edit/delete applications directly.
    // Destroy might be used to remove spam applications? Needs careful consideration.
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(JobApplication $application) { abort(404); }
    public function destroy(JobApplication $application) {
        // TODO: Add Authorization
        // $manager = Auth::user(); ... check ownership ...
        // $application->delete();
        // return redirect()->route('company-manager.job-applications.index')->with('success', 'Application removed.');
        abort(404); // Safer default
     }

}