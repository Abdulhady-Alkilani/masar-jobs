<?php

namespace App\Http\Controllers\API\V1\Admin; // Correct Namespace

use App\Http\Controllers\Controller;
use App\Models\Company; // Assuming Company model has a status field
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyRequestController extends Controller
{
     // TODO: Add Authorization check (Middleware/Gate/Policy) for all methods - Admin Only

    /**
     * Display a listing of pending company creation requests.
     * Route: GET /admin/company-requests
     */
    public function index(Request $request)
    {
        // Assuming 'pending' is the status for new requests
        $pendingCompanies = Company::where('status', 'pending') // Adjust status field/value as needed
                                   ->with('user:UserID,first_name,last_name,email') // Load associated user info
                                   ->paginate(15);

        // Consider CompanyResourceCollection
        return response()->json($pendingCompanies);
    }

    /**
     * Approve a company creation request.
     * Route: PUT /admin/company-requests/{company}/approve
     */
    public function approve(Request $request, Company $company) // Route model binding
    {
        // TODO: Add Authorization check - Admin Only

        // Check if company is actually pending
        if ($company->status !== 'pending') { // Adjust status field/value
            return response()->json(['message' => 'Company is not pending approval.'], 400);
        }

        $company->update(['status' => 'approved']); // Adjust status field/value

        // TODO: Optionally send notification to the company manager user ($company->user)

        // Consider CompanyResource
        return response()->json(['message' => 'Company approved successfully.', 'company' => $company]);
    }

    /**
     * Reject a company creation request.
     * Route: PUT /admin/company-requests/{company}/reject
     */
    public function reject(Request $request, Company $company) // Route model binding
    {
        // TODO: Add Authorization check - Admin Only

        // Check if company is actually pending
        if ($company->status !== 'pending') { // Adjust status field/value
            return response()->json(['message' => 'Company is not pending approval.'], 400);
        }

        // Option 1: Set status to 'rejected'
        $company->update(['status' => 'rejected']); // Adjust status field/value

        // Option 2: Delete the company record entirely
        // $company->delete();

        // TODO: Optionally send notification to the company manager user ($company->user)

         if (isset($company) && $company->exists) { // If status was updated
              return response()->json(['message' => 'Company rejected successfully.', 'company' => $company]);
         } else { // If deleted
             return response()->json(['message' => 'Company rejected and removed successfully.']);
         }
    }
}