<?php

namespace App\Http\Controllers\API\V1\Company; // Correct Namespace

use App\Http\Controllers\Controller;
use App\Models\JobOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagedJobOpportunityController extends Controller
{
    /**
     * Display a listing of the resource managed by the company manager.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        // TODO: Add Authorization check - Ensure user is a Company Manager
        if ($user->type !== 'مدير شركة') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Get jobs posted by this manager
        $jobs = JobOpportunity::where('UserID', $user->UserID)
                              ->latest()
                              ->paginate(15);

        // Consider JobOpportunityResourceCollection
        return response()->json($jobs);
    }

    /**
     * Store a newly created resource in storage managed by the company manager.
     */
    public function store(Request $request)
    {
         $user = $request->user();
         // TODO: Add Authorization check - Ensure user is a Company Manager
         if ($user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

        $validatedData = $request->validate([
            // UserID is automatically set to the manager
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'nullable|string|max:255',
            'Date' => 'required|date',
            'Skills' => 'nullable|string',
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:Date',
            'Status' => 'required|string|in:مفعل,معلق,محذوف', // Manager sets status
        ]);

        // Assign the manager's UserID
        $validatedData['UserID'] = $user->UserID;

        $job = JobOpportunity::create($validatedData);
         // Consider JobOpportunityResource
        return response()->json($job, 201);
    }

    /**
     * Display the specified resource managed by the company manager.
     */
    public function show(Request $request, $id) // Use ID, not route model binding yet for auth check
    {
        $user = $request->user();
        $job = JobOpportunity::findOrFail($id);

        // Authorization: Check if the job belongs to the manager
        if ($job->UserID !== $user->UserID || $user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Consider JobOpportunityResource
        return response()->json($job);
    }

    /**
     * Update the specified resource in storage managed by the company manager.
     */
    public function update(Request $request, $id) // Use ID
    {
        $user = $request->user();
        $job = JobOpportunity::findOrFail($id);

         // Authorization: Check if the job belongs to the manager
        if ($job->UserID !== $user->UserID || $user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            // Manager cannot change UserID
            'Job Title' => 'sometimes|required|string|max:255',
            'Job Description' => 'sometimes|required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'nullable|string|max:255',
            'Date' => 'sometimes|required|date',
            'Skills' => 'nullable|string',
            'Type' => 'sometimes|required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:Date',
            'Status' => 'sometimes|required|string|in:مفعل,معلق,محذوف',
        ]);

        $job->update($validatedData);
         // Consider JobOpportunityResource
        return response()->json($job);
    }

    /**
     * Remove the specified resource from storage managed by the company manager.
     */
    public function destroy(Request $request, $id) // Use ID
    {
        $user = $request->user();
        $job = JobOpportunity::findOrFail($id);

        // Authorization: Check if the job belongs to the manager
        if ($job->UserID !== $user->UserID || $user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
        }

        // TODO: Consider what happens to applications for this job (cascade delete?)
        $job->delete();

        return response()->json(null, 204);
    }
}