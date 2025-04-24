<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User; // To list managers when creating/editing
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Needed for the Admin check (though middleware handles primary)

class CompanyController extends Controller
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
        // Reuse public index view or create admin specific one
         $query = Company::query()->with('user'); // Load the manager user

         if ($request->filled('search')) {
             // Add search logic here
         }
         // Add filtering by status if applicable

         $companies = $query->latest()->paginate(20);
         return view('admin.companies.index', compact('companies')); // Use admin view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Find managers without companies to assign
        $managers = User::where('type', 'مدير شركة')->whereDoesntHave('company')->orderBy('username')->pluck('username', 'UserID');
        return view('admin.companies.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Reuse validation/logic from the public CompanyController store method (Admin version)
        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID|unique:companies,UserID',
            'Name' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255|unique:companies,Email',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Web site' => 'nullable|url|max:2048',
            'Status' => 'nullable|string', // Admin sets status directly if needed
        ]);

        Company::create($validatedData);

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
         // Reuse public show view or create admin specific one
         $company->load(['user', 'jobOpportunities' => function ($query) {
             $query->latest(); // Show all jobs, not just active for admin
         }]);
         return view('admin.companies.show', compact('company')); // Use admin view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
         // Find managers (might allow changing manager, though complex)
         // $managers = User::where('type', 'مدير شركة')->orderBy('username')->pluck('username', 'UserID');
         // $company->load('user'); // Load current manager
        return view('admin.companies.edit', compact('company'/*, 'managers'*/));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
         // Reuse validation/logic from the public CompanyController update method (Admin version)
         // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            // 'UserID' => ['sometimes', 'required', Rule::exists('users','UserID'), Rule::unique('companies','UserID')->ignore($company->CompanyID, 'CompanyID')], // Allow changing manager?
            'Name' => 'required|string|max:255',
            'Email' => ['nullable', 'email', 'max:255', Rule::unique('companies','Email')->ignore($company->CompanyID, 'CompanyID')],
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Web site' => 'nullable|url|max:2048',
            'Status' => 'nullable|string', // Admin sets status
        ]);

        $company->update($validatedData);

        return redirect()->route('admin.companies.show', $company)->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Reuse logic from public CompanyController destroy method (Admin version)
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully.');
    }
}