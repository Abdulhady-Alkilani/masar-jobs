<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // For unique checks on update

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource. (Public)
     */
    public function index(Request $request)
    {
        // Add filtering/searching by name, city, country
        $query = Company::query(); // Add status check if companies need approval

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('Name', 'like', "%{$searchTerm}%")
                  ->orWhere('Description', 'like', "%{$searchTerm}%")
                  ->orWhere('City', 'like', "%{$searchTerm}%")
                  ->orWhere('Country', 'like', "%{$searchTerm}%");
            });
        }

        $companies = $query->latest()->paginate(15);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     * Primarily handled by Admin or potentially a Company Request flow.
     * A standard 'create' form might be less common here unless admin adds directly.
     */
    public function create()
    {
        // TODO: Add Authorization (Admin only? Or part of request flow?)
        if (Auth::user()->type !== 'Admin') {
             abort(403);
        }
        // $managers = User::where('type', 'مدير شركة')->whereDoesntHave('company')->get(); // Find managers without companies
        return view('companies.create'/*, compact('managers')*/);
    }

    /**
     * Store a newly created resource in storage.
     * Primarily by Admin.
     */
    public function store(Request $request)
    {
         // TODO: Add Authorization (Admin only?)
         if (Auth::user()->type !== 'Admin') {
             abort(403);
         }

        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID|unique:companies,UserID', // Manager ID, ensure they exist and don't have a company yet
            'Name' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255|unique:companies,Email',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Web site' => 'nullable|url|max:2048',
            // Add status if admin approves directly
        ]);

        Company::create($validatedData);

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully!');
    }

    /**
     * Display the specified resource. (Public)
     */
    public function show(Company $company) // Route Model Binding
    {
        $company->load(['user', 'jobOpportunities' => function ($query) { // Load manager and active jobs
            $query->where('Status', 'مفعل')->latest();
        }]);
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     * Accessible by assigned Manager or Admin
     */
    public function edit(Company $company)
    {
        // TODO: Add Authorization (Policy: user can update company?)
        if (Auth::id() !== $company->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
        }
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     * Accessible by assigned Manager or Admin
     */
    public function update(Request $request, Company $company)
    {
        // TODO: Add Authorization
        if (Auth::id() !== $company->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
        }

        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            // UserID usually shouldn't be changed here
            'Name' => 'required|string|max:255',
            'Email' => ['nullable', 'email', 'max:255', Rule::unique('companies','Email')->ignore($company->CompanyID, 'CompanyID')],
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Web site' => 'nullable|url|max:2048',
            // Admin might update status ('Active', 'Pending', 'Rejected')
            'Status' => ['sometimes', 'required', 'string', /* Rule::in([...]), */ Rule::requiredIf(Auth::user()->type === 'Admin')]
        ]);

        $company->update($validatedData);

        $redirectRoute = Auth::user()->type === 'Admin' ? 'admin.companies.show' : 'company-manager.company-profile.show'; // Adjust route names if needed
        return redirect()->route($redirectRoute, $company)->with('success', 'Company details updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * Primarily Admin action.
     */
    public function destroy(Company $company)
    {
       // TODO: Add Authorization (Admin only?)
       if (Auth::user()->type !== 'Admin') {
           abort(403);
       }

       // Consider implications: what happens to company manager user, jobs, etc.?
       $company->delete();

       return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully!');
    }
}