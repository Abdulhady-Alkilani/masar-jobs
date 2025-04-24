<?php

namespace App\Http\Controllers;

use App\Models\JobOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Skill; // If relating skills

class JobOpportunityController extends Controller
{
    /**
     * Display a listing of the resource. (Public)
     */
    public function index(Request $request)
    {
        // Add filtering/searching by title, type, location, skills etc.
        $query = JobOpportunity::query()->where('Status', 'مفعل'); // Show only active jobs

        // Example Search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('Job Title', 'like', "%{$searchTerm}%")
                  ->orWhere('Job Description', 'like', "%{$searchTerm}%")
                  ->orWhere('Skills', 'like', "%{$searchTerm}%"); // Simple text search on skills column
            });
        }

        // Example Filter by Type
        if ($request->filled('type') && in_array($request->input('type'), ['وظيفة', 'تدريب'])) {
             $query->where('Type', $request->input('type'));
        }

        $jobOpportunities = $query->latest()->paginate(15);
        return view('jobs.index', compact('jobOpportunities'));
    }

    /**
     * Show the form for creating a new resource.
     * Accessible by Company Manager or Admin
     */
    public function create()
    {
        // TODO: Add Authorization (Policy or Gate for 'create job opportunity')
        if (!in_array(Auth::user()->type, ['مدير شركة', 'Admin'])) {
             abort(403, 'Unauthorized action.');
        }
        // $skills = Skill::all(); // Pass skills if using a multi-select for skills
        return view('jobs.create'/*, compact('skills')*/);
    }

    /**
     * Store a newly created resource in storage.
     * Accessible by Company Manager or Admin
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization
        if (!in_array(Auth::user()->type, ['مدير شركة', 'Admin'])) {
             abort(403, 'Unauthorized action.');
        }

        // TODO: Use Form Request Validation (e.g., StoreJobOpportunityRequest)
        $validatedData = $request->validate([
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'required|string|max:255',
            'Skills' => 'nullable|string', // Or handle as array if needed
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:today',
            // Admin might need to select the UserID (company), Manager uses their own ID
        ]);

        $jobOpportunity = JobOpportunity::create([
            // If admin creates, they might select the company UserID from the form
            // If manager creates, it should be their UserID (or their company's UserID if companies linked differently)
            'UserID' => Auth::id(), // Assuming manager's user ID is the creator
            'Job Title' => $validatedData['Job Title'],
            'Job Description' => $validatedData['Job Description'],
            'Qualification' => $validatedData['Qualification'],
            'Site' => $validatedData['Site'],
            'Skills' => $validatedData['Skills'], // Process if it's an array from multi-select
            'Type' => $validatedData['Type'],
            'End Date' => $validatedData['End Date'],
            'Date' => now(), // Posting date
            'Status' => 'مفعل', // Default status
        ]);

        // TODO: If skills are relational (Many-to-Many), sync them here:
        // $jobOpportunity->requiredSkills()->sync($request->input('skill_ids', []));

        return redirect()->route('jobs.show', $jobOpportunity)->with('success', 'Job opportunity created successfully!');
    }

    /**
     * Display the specified resource. (Public)
     */
    public function show(JobOpportunity $jobOpportunity) // Route Model Binding
    {
        // Check if job is active or if user is allowed to see non-active (e.g., owner/admin)
        if ($jobOpportunity->Status !== 'مفعل' && (!Auth::check() || (Auth::id() !== $jobOpportunity->UserID && Auth::user()->type !== 'Admin'))) {
             abort(404); // Or show a specific "not available" view
        }
        $jobOpportunity->load('user.company'); // Eager load creator/company info
        return view('jobs.show', compact('jobOpportunity'));
    }

    /**
     * Show the form for editing the specified resource.
     * Accessible by creator (Manager) or Admin
     */
    public function edit(JobOpportunity $jobOpportunity)
    {
        // TODO: Add Authorization (Policy: user can update job?)
        if (Auth::id() !== $jobOpportunity->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
         }
        // $skills = Skill::all();
        return view('jobs.edit', compact('jobOpportunity'/*, 'skills'*/));
    }

    /**
     * Update the specified resource in storage.
     * Accessible by creator (Manager) or Admin
     */
    public function update(Request $request, JobOpportunity $jobOpportunity)
    {
        // TODO: Add Authorization
        if (Auth::id() !== $jobOpportunity->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
        }

        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'required|string|max:255',
            'Skills' => 'nullable|string',
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:today',
            'Status' => 'sometimes|required|string|in:مفعل,معلق,محذوف', // Admin or Manager can change status
        ]);

        $jobOpportunity->update($validatedData);

        // TODO: Sync skills if relational
        // $jobOpportunity->requiredSkills()->sync($request->input('skill_ids', []));

        return redirect()->route('jobs.show', $jobOpportunity)->with('success', 'Job opportunity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * Accessible by creator (Manager) or Admin
     */
    public function destroy(JobOpportunity $jobOpportunity)
    {
       // TODO: Add Authorization
       if (Auth::id() !== $jobOpportunity->UserID && Auth::user()->type !== 'Admin') {
           abort(403, 'Unauthorized action.');
       }

       // Consider implications: what happens to applications? (CASCADE constraint?)
       $jobOpportunity->delete();

       // Redirect to relevant dashboard or index
       $redirectRoute = Auth::user()->type === 'Admin' ? 'admin.job-opportunities.index' : 'company-manager.job-opportunities.index';
       return redirect()->route($redirectRoute)->with('success', 'Job opportunity deleted successfully!');
    }
}