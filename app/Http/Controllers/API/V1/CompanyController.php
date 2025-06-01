<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource. (Public/Admin)
     */
    public function index(Request $request)
    {
        // TODO: Add filtering (by country, city?), pagination, search
        // TODO: Potentially differentiate between public view (approved only?) and admin view (all)
        $companies = Company::paginate(15); // Add ->where('status', 'approved') for public?
        // Consider CompanyResourceCollection
        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage. (Admin Only?)
     * Or maybe for company registration requests? -> Handled by CompanyRequestController?
     * Assuming Admin creates companies directly here for now.
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID|unique:companies,UserID', // One user per company
            'Name' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Media' => 'nullable|string', // Handle file uploads or JSON array?
            'Web site' => 'nullable|url|max:255',
            // 'Status' => 'required|string|in:approved,pending,rejected' // If admin sets status
        ]);

        // $validatedData['Status'] = 'approved'; // If admin creates directly
        $company = Company::create($validatedData);
        // Consider CompanyResource
        return response()->json($company, 201);
    }

    /**
     * Display the specified resource. (Public/Admin)
     */
    public function show($id) // Route model binding: Company $company
    {
        $company = Company::findOrFail($id);
        // TODO: Check status if public view should only show approved?
        // Consider CompanyResource
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage. (Admin Only)
     */
    public function update(Request $request, $id) // Route model binding: Company $company
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $company = Company::findOrFail($id);

        $validatedData = $request->validate([
             // Cannot change UserID easily?
            'Name' => 'sometimes|required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Media' => 'nullable|string',
            'Web site' => 'nullable|url|max:255',
             // 'Status' => 'sometimes|required|string|in:approved,pending,rejected' // Admin can change status
        ]);

        $company->update($validatedData);
        // Consider CompanyResource
        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage. (Admin Only)
     */
    public function destroy($id) // Route model binding: Company $company
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(null, 204);
    }

    // --- Methods for Company Manager ---

    /**
     * Display the company managed by the current user.
     */
    public function showManagedCompany(Request $request)
    {
        $user = $request->user();
        // TODO: Add Authorization check - Ensure user is a Company Manager
        if ($user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = $user->company; // Assumes hasOne relationship named 'company' in User model

        if (!$company) {
            return response()->json(['message' => 'No company associated with this manager.'], 404);
        }

        // Consider CompanyResource
        return response()->json($company);
    }

    /**
     * Update the company managed by the current user.
     */
    public function updateManagedCompany(Request $request)
    {
         $user = $request->user();
        // TODO: Add Authorization check - Ensure user is a Company Manager
         if ($user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'No company associated with this manager.'], 404);
        }

        // Allow manager to update only specific fields
        $validatedData = $request->validate([
            'Name' => 'sometimes|required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Media' => 'nullable|string',
            'Web site' => 'nullable|url|max:255',
        ]);

        $company->update($validatedData);
         // Consider CompanyResource
        return response()->json($company);
    }
}