<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company; // Assuming requests are stored as companies with a status
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyRequestApprovalController extends Controller
{
    /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isAdmin'*/]);
    }

    /**
     * Display a listing of pending company requests.
     */
    public function index()
    {
        // Assumes a 'Status' column ('Pending', 'Approved', 'Rejected') exists on the Company model
        $pendingCompanies = Company::where('Status', 'Pending') // Adjust status name if needed
                                   ->with('user') // Load the requesting user
                                   ->latest()
                                   ->paginate(20);

        return view('admin.company_requests.index', compact('pendingCompanies'));
    }

    /**
     * Display the specified pending company request.
     */
    public function show(Company $company) // Use company model directly
    {
        // Ensure it's actually a pending request for clarity, although route might handle this
        if ($company->Status !== 'Pending') {
            // Maybe redirect to the main company view?
            return redirect()->route('admin.companies.show', $company);
        }
        $company->load('user');
        return view('admin.company_requests.show', compact('company'));
    }

    /**
     * Update the specified resource in storage (Approve or Reject).
     * Using UPDATE (PUT/PATCH) method for approval/rejection.
     */
    public function update(Request $request, Company $company)
    {
        // TODO: Form Request Validation
        $validatedData = $request->validate([
            'action' => 'required|string|in:approve,reject',
            'rejection_reason' => 'nullable|string|max:1000|required_if:action,reject',
        ]);

        if ($company->Status !== 'Pending') {
            return back()->with('error', 'This company request has already been processed.');
        }

        if ($validatedData['action'] === 'approve') {
            $company->update(['Status' => 'Approved']); // Or 'Active'
            // TODO: Notify manager of approval
            return redirect()->route('admin.company-requests.index')
                             ->with('success', "Company '{$company->Name}' approved successfully.");
        } elseif ($validatedData['action'] === 'reject') {
            $company->update([
                'Status' => 'Rejected',
                // Optionally store rejection reason if you add a column
                // 'rejection_reason' => $validatedData['rejection_reason']
                ]);
            // TODO: Notify manager of rejection and reason
             return redirect()->route('admin.company-requests.index')
                             ->with('success', "Company '{$company->Name}' rejected successfully.");
        }

        return back()->with('error', 'Invalid action specified.');
    }


    // Admin doesn't typically create/edit requests this way, they approve/reject existing ones.
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(Company $company) { abort(404); }

     /**
      * Optionally allow deleting a PENDING request entirely.
      */
    public function destroy(Company $company)
    {
         if ($company->Status !== 'Pending') {
            return back()->with('error', 'Cannot delete an already processed company request.');
         }
         // TODO: Notify manager?
         $company->delete();
         return redirect()->route('admin.company-requests.index')->with('success', 'Pending company request deleted.');
    }
}