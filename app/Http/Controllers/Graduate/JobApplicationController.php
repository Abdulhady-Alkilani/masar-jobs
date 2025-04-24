<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // If handling CV uploads

class JobApplicationController extends Controller
{
    /**
     * Apply middleware for authentication and graduate role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isGraduate'*/]);
    }

    /**
     * Display a listing of the graduate's job applications.
     */
    public function index()
    {
        $user = Auth::user();
        $applications = JobApplication::where('UserID', $user->UserID)
            ->with('jobOpportunity') // Eager load job details
            ->latest('Date') // Or created_at
            ->paginate(15);

        return view('graduate.applications.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource (Applying for a job).
     * This is usually initiated from the JobOpportunity show page, not a dedicated create page here.
     */
    public function create()
    {
        // Redirect or show error, application should happen via job listing
        return redirect()->route('jobs.index')->with('info', 'Please find a job to apply for first.');
    }

    /**
     * Store a newly created resource in storage (Submit an application).
     * Often triggered from a form on the JobOpportunity show page.
     */
    public function store(Request $request, JobOpportunity $jobOpportunity = null)
    {
        // If JobOpportunity is not passed via route model binding, get it from request
        if (!$jobOpportunity && $request->filled('job_id')) {
             $jobOpportunity = JobOpportunity::findOrFail($request->input('job_id'));
        } elseif (!$jobOpportunity) {
             return back()->with('error', 'Job opportunity not specified.');
        }

        $user = Auth::user();

        // Check if already applied
        $existingApplication = JobApplication::where('UserID', $user->UserID)
                                            ->where('JobID', $jobOpportunity->JobID)
                                            ->exists();
        if ($existingApplication) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Check if job is still active/accepting applications
        if ($jobOpportunity->Status !== 'مفعل' || ($jobOpportunity->{'End Date'} && $jobOpportunity->{'End Date'} < now())) {
             return back()->with('error', 'This job is no longer accepting applications.');
        }


        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'Description' => 'nullable|string|max:2000', // Cover letter / notes
            'CV' => 'required|file|mimes:pdf,doc,docx|max:5120', // Example: 5MB PDF/Word CV required
        ]);

        // Handle CV upload
        $cvPath = null;
        if ($request->hasFile('CV') && $request->file('CV')->isValid()) {
            // Store CV specific to user/job application for organization
            $cvPath = $request->file('CV')->store("cvs/{$user->UserID}", 'private'); // Use 'private' disk if sensitive
            // Ensure 'private' disk is configured in config/filesystems.php
        } else {
            return back()->with('error', 'CV upload failed or missing.');
        }


        JobApplication::create([
            'UserID' => $user->UserID,
            'JobID' => $jobOpportunity->JobID,
            'Status' => 'Pending', // Initial status
            'Date' => now(),
            'Description' => $validatedData['Description'],
            'CV' => $cvPath,
        ]);

        // Optionally notify company manager

        return redirect()->route('graduate.applications.index')->with('success', 'Application submitted successfully!');
    }

    /**
     * Display the specified resource (view a specific application).
     */
    public function show(JobApplication $application) // Use Route Model Binding
    {
        // TODO: Add Authorization (Ensure this application belongs to the logged-in user)
        if ($application->UserID !== Auth::id()) {
             abort(403);
        }

        $application->load('jobOpportunity.user.company'); // Load related data
        return view('graduate.applications.show', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     * Graduates usually cannot edit submitted applications. Maybe allow withdrawal?
     */
    public function edit(JobApplication $application)
    {
        abort(404); // Or implement withdrawal logic
    }

    /**
     * Update the specified resource in storage.
     * Graduates usually cannot edit submitted applications.
     */
    public function update(Request $request, JobApplication $application)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage (Withdraw application).
     */
    public function destroy(JobApplication $application)
    {
         // TODO: Add Authorization (Ensure this application belongs to the logged-in user)
        if ($application->UserID !== Auth::id()) {
            abort(403);
        }

        // Optional: Check if withdrawal is allowed based on status
        if (!in_array($application->Status, ['Pending', 'Reviewed'])) { // Example: Allow withdrawal only in early stages
            return back()->with('error', 'Cannot withdraw application at this stage.');
        }

        // TODO: Delete the stored CV file
        if ($application->CV) {
            Storage::disk('private')->delete($application->CV); // Use correct disk
        }

        $application->delete();

        // Optionally notify company manager about withdrawal

        return redirect()->route('graduate.applications.index')->with('success', 'Application withdrawn successfully.');
    }
} 