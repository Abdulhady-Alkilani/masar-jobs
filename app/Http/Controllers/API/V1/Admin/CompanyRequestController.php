<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- استيراد Storage

use App\Models\Company;
use App\Models\User; // Needed to access user data, but NOT to change type here


// Consider using API Resources if you need to format the output
// use App\Http\Resources\CompanyResource;
// use App\Http\Resources\CompanyCollection;

class ApiAdminCompanyRequestController extends Controller
{
     /**
      * Instantiate a new controller instance.
      * Apply admin check middleware to all methods in this controller.
      */
    // public function __construct()
    // {
    //     // TODO: Apply middleware to ensure only admins can access these routes
    //     // $this->middleware('isAdmin');
    // }


    /**
     * Display a listing of pending company creation requests (Companies with status 'pending').
     * Route: GET /api/v1/admin/company-requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        // Example using Gate: $this->authorize('viewAny', Company::class);

        $pendingCompanies = Company::where('status', 'pending')
                                   ->with('user:UserID,first_name,last_name,email,type') // Eager load user details
                                   ->latest()
                                   ->paginate(15);

        // Consider using CompanyCollection
        return response()->json($pendingCompanies);
    }

    /**
     * Approve a company creation request.
     * Changes company status to 'approved' and moves the media file.
     * The user's type is assumed to be 'مدير شركة' already.
     * Route: PUT /api/v1/admin/company-requests/{company}/approve
     *
     * @param  \App\Models\Company  $company // Route model binding (fetches the company record)
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Company $company)
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        // Example using Gate: $this->authorize('approve', $company);

        // Ensure the company is currently pending
        if ($company->status !== 'pending') {
            return response()->json(['message' => 'Company is not pending approval.'], 400); // Bad Request
        }

        // Fetch the associated user (should be a 'مدير شركة' user)
        $user = $company->user;

        // Basic check if associated user exists and is the expected type
        if (!$user || $user->type !== 'مدير شركة') {
             $logMessage = "Admin attempted to approve company ID {$company->CompanyID}. ";
             if (!$user) $logMessage .= "No associated user found.";
             else $logMessage .= "Associated user UserID {$user->UserID} is type {$user->type}, not 'مدير شركة'.";
             Log::error($logMessage);
             // You might return 400 Bad Request or 404 Not Found depending on how you frame the error
             return response()->json(['message' => 'Associated user is not valid for company approval.'], 400);
        }


        // Get the temporary media path from the company record
        $temporaryMediaPath = $company->Media;
        $permanentMediaPath = $temporaryMediaPath; // Initialize with temp path

        // Use transactions for atomicity
        DB::beginTransaction();

        try {
            // --- Handle Media File (Move from pending to permanent) ---
            if ($temporaryMediaPath && Storage::disk('public')->exists($temporaryMediaPath)) {
                $permanentMediaPath = str_replace('companies/pending/media', 'companies/media', $temporaryMediaPath);
                Storage::disk('public')->createDirectory('companies/media');
                Storage::disk('public')->move($temporaryMediaPath, $permanentMediaPath);
                 Log::info("Moved company media file from {$temporaryMediaPath} to {$permanentMediaPath} for CompanyID {$company->CompanyID} during approval.");
            } else {
                 $permanentMediaPath = null; // Ensure the DB field is null
                 if ($temporaryMediaPath) {
                     Log::warning("Company media path {$temporaryMediaPath} found in DB for CompanyID {$company->CompanyID} but file does not exist on disk during approval.");
                 }
            }
            // --- End Handle Media File ---

            // Update company status to 'approved' and save the permanent media path
            $company->status = 'approved';
            $company->Media = $permanentMediaPath; // Save the new permanent path
            $company->save();

            // !!! IMPORTANT: In this scenario, the user is ALREADY 'مدير شركة'.
            // !!! We do NOT change the user type here.

            // TODO: Send notification to the user that their company request was approved

            DB::commit(); // Apply changes

            // Optionally reload user relationship
            $company->load('user');

            return response()->json([
                'message' => 'Company approved successfully.',
                'company' => $company
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error approving company ID {$company->CompanyID}: {$e->getMessage()}");
            return response()->json(['message' => 'Failed to approve company due to a server error.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Reject a company creation request.
     * Deletes the company record and the associated temporary media file.
     * Route: PUT /api/v1/admin/company-requests/{company}/reject
     *
     * @param  \App\Models\Company  $company // Route model binding
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Company $company)
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        // Example using Gate: $this->authorize('reject', $company);

        // Ensure the company is currently pending
        if ($company->status !== 'pending') {
            return response()->json(['message' => 'Company is not pending rejection.'], 400); // Bad Request
        }

        // Get the temporary media path before deleting the record
        $temporaryMediaPath = $company->Media;

        // Use transactions for safety
         DB::beginTransaction();
        try {
            // Delete the company record.
            $company->delete();

            // --- Handle Media File (Delete temporary file) ---
             if ($temporaryMediaPath && Storage::disk('public')->exists($temporaryMediaPath)) {
                 Storage::disk('public')->delete($temporaryMediaPath);
                 Log::info("Deleted temporary company media file {$temporaryMediaPath} for rejected CompanyID {$company->CompanyID}.");
             }
            // --- End Handle Media File ---


            // TODO: Send notification to the user that their company request was rejected

             DB::commit();

            return response()->json(['message' => 'Company rejected and removed successfully.']); // 200 OK

        } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Error rejecting company ID {$company->CompanyID} (delete+cleanup): {$e->getMessage()}");
            return response()->json(['message' => 'Failed to reject company due to a server error.', 'error' => $e->getMessage()], 500);
        }
    }
}