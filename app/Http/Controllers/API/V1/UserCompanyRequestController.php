<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // <-- استيراد Storage

class UserCompanyRequestController extends Controller
{
    /**
     * Store a new company creation request, including potential media file upload.
     * Route: POST /api/v1/company-requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // --- Authorization & Eligibility Checks ---
        if ($user->type === 'مدير شركة' || $user->type === 'خبير استشاري' || $user->type === 'Admin') {
             return response()->json(['message' => 'You already have a specialized user type.'], 403);
        }
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

        // --- Handle File Upload for Media ---
        $mediaPath = null;
        if ($request->hasFile('Media')) {
            // Store the file temporarily in a 'pending' folder on the public disk
            // Laravel's store method generates a unique filename
            $mediaPath = $request->file('Media')->store('companies/pending/media', 'public');
             // We only store the path relative to the disk root ('companies/pending/media/...')
        }
        // --- End File Upload ---

        // Use DB transaction for safety (especially if adding more steps)
        DB::beginTransaction();

        try {
             $company = Company::create([
                 'UserID' => $user->UserID,
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

             \Log::error("Error submitting company request for UserID {$user->UserID}: {$e->getMessage()}");
             return response()->json(['message' => 'Failed to submit company request due to a server error.', 'error' => $e->getMessage()], 500);
        }
    }
}