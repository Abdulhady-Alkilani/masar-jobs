<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOpportunity;
use App\Models\User; // For selecting company manager
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobOpportunityController extends Controller
{
     /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isAdmin'*/]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobOpportunity::query()->with('user.company'); // Load creator/company

        // Add admin filtering/searching
        if ($request->filled('search')) { }
        if ($request->filled('type')) { }
        if ($request->filled('status')) { }
        if ($request->filled('company_id')) {
            $query->whereHas('user.company', fn($q) => $q->where('companies.CompanyID', $request->input('company_id')));
        }


        $jobOpportunities = $query->latest('Date')->paginate(20);
        // $companies = Company::orderBy('Name')->pluck('Name', 'CompanyID'); // For filter dropdown
        return view('admin.job_opportunities.index', compact('jobOpportunities'/*, 'companies'*/));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Admin selects the company manager (UserID) posting the job
        $managers = User::where('type', 'مدير شركة')->with('company')->get()->pluck('company.Name', 'UserID'); // Get UserID => Company Name
        return view('admin.job_opportunities.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Reuse validation from public controller, add UserID validation
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // Admin selects manager
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'required|string|max:255',
            'Skills' => 'nullable|string',
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:today',
            'Status' => 'required|string|in:مفعل,معلق,محذوف', // Admin sets status
        ]);

        JobOpportunity::create($validatedData + ['Date' => now()]);

        return redirect()->route('admin.job-opportunities.index')->with('success', 'Job Opportunity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobOpportunity $jobOpportunity)
    {
        $jobOpportunity->load('user.company', 'jobApplications.user'); // Load related data for admin view
        return view('admin.job_opportunities.show', compact('jobOpportunity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobOpportunity $jobOpportunity)
    {
         $managers = User::where('type', 'مدير شركة')->with('company')->get()->pluck('company.Name', 'UserID');
         $jobOpportunity->load('user');
        return view('admin.job_opportunities.edit', compact('jobOpportunity', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobOpportunity $jobOpportunity)
    {
        // Reuse validation from public controller, add UserID, Status
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // Admin might change owner?
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'required|string|max:255',
            'Skills' => 'nullable|string',
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:today',
            'Status' => 'required|string|in:مفعل,معلق,محذوف',
        ]);

        $jobOpportunity->update($validatedData);

        return redirect()->route('admin.job-opportunities.show', $jobOpportunity)->with('success', 'Job Opportunity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobOpportunity $jobOpportunity)
    {
        // Reuse logic from public controller destroy method
        $jobOpportunity->delete();
        return redirect()->route('admin.job-opportunities.index')->with('success', 'Job Opportunity deleted successfully.');
    }
} 