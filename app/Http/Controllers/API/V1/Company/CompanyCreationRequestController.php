<?php

namespace App\Http\Controllers\API\V1\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company; // Need Company model to create the record
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // For file handling


class CompanyCreationRequestController extends Controller
{
    // Optional: Apply middleware here to ensure user is a Company Manager
    // public function __construct()
    // {
    //     $this->middleware('isCompanyManager'); // Assuming you have this middleware
    // }

    /**
     * Submit a new company creation request for the authenticated company manager.
     * Route: POST /api/v1/company-manager/company-request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user(); // Authenticated user

        // --- Authorization & Eligibility Checks ---
        // 1. Ensure the user is actually a 'مدير شركة' (redundant if middleware is applied, but safe)
        if ($user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Only company managers can request company creation.'], 403);
        }
        // 2. Check if the user already has a company linked (pending or approved)
        // A manager should only have ONE company they are requesting or managing.
        if ($user->company()->exists()) {
             return response()->json(['message' => 'You already have a company associated with your account.'], 409); // Conflict
        }
        // --- End Checks ---


        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            // Validation for file upload - assuming image for logo
            'Media' => 'nullable|image|max:5120', // Accepts image files, max 5MB (adjust size)
            'Web site' => 'nullable|url|max:255',
        ]);

        // --- Handle File Upload for Media (Temporary storage) ---
        $mediaPath = null;
        if ($request->hasFile('Media')) {
            // Store the file temporarily in a 'pending' folder
            $mediaPath = $request->file('Media')->store('companies/pending/media', 'public');
        }
        // --- End File Upload ---

        // Use DB transaction for safety
        DB::beginTransaction();

        try {
             // Create the Company record with 'pending' status and link it to the user
             $company = Company::create([
                 'UserID' => $user->UserID, // Link the company to the current user (manager)
                 'Name' => $validatedData['Name'],
                 'Email' => $validatedData['Email'] ?? null,
                 'Phone' => $validatedData['Phone'] ?? null,
                 'Description' => $validatedData['Description'] ?? null,
                 'Country' => $validatedData['Country'] ?? null,
                 'City' => $validatedData['City'] ?? null,
                 'Detailed Address' => $validatedData['Detailed Address'] ?? null,
                 'Media' => $mediaPath, // Save the temporary stored path
                 'Web site' => $validatedData['Web site'] ?? null,
                 'status' => 'pending', // Set status as 'pending'
             ]);

             // TODO: Send notification to Admin about the new company request

             DB::commit(); // Apply changes

             return response()->json([
                 'message' => 'Company creation request submitted successfully. Waiting for admin approval.',
                 'company' => $company // Return the company record with temporary path
             ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Revert changes

            // If a file was uploaded and DB transaction failed, delete the temporary file
            if ($mediaPath) {
                 Storage::disk('public')->delete($mediaPath);
            }

             \Log::error("Error submitting company creation request for UserID {$user->UserID}: {$e->getMessage()}");
             return response()->json(['message' => 'Failed to submit company creation request due to a server error.', 'error' => $e->getMessage()], 500);
        }
    }

    // Optional: Maybe a show method for the manager to see their pending request?
    // public function show(Request $request) { ... }
}