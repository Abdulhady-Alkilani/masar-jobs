<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\JobOpportunity;
use Illuminate\Http\Request;

class JobOpportunityController extends Controller
{
    /**
     * Display a listing of the resource. (Public)
     */
    public function index(Request $request)
    {
        // TODO: Add filtering (by type, skills, location?), sorting, pagination, search
        $jobs = JobOpportunity::with('user:UserID,first_name,last_name') // Eager load poster name
                              ->where('Status', 'مفعل') // Show only active jobs publicly?
                              ->latest()
                              ->paginate(15);

        // Consider using JobOpportunityResourceCollection
        return response()->json($jobs);
    }

    /**
     * Store a newly created resource in storage. (Admin Only?)
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // Admin assigns user
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'nullable|string|max:255',
            'Date' => 'required|date',
            'Skills' => 'nullable|string', // Consider validation if JSON or comma-separated
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:Date',
            'Status' => 'required|string|in:مفعل,معلق,محذوف',
        ]);

        $job = JobOpportunity::create($validatedData);
        // Consider using JobOpportunityResource
        return response()->json($job, 201);
    }

    /**
     * Display the specified resource. (Public)
     */
    public function show($id) // Route model binding: JobOpportunity $jobOpportunity
    {
        $job = JobOpportunity::with('user:UserID,first_name,last_name')->findOrFail($id);
         // Consider using JobOpportunityResource
        return response()->json($job);
    }

    /**
     * Update the specified resource in storage. (Admin Only?)
     */
    public function update(Request $request, $id) // Route model binding: JobOpportunity $jobOpportunity
    {
        // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        $job = JobOpportunity::findOrFail($id);

        $validatedData = $request->validate([
            'UserID' => 'sometimes|required|exists:users,UserID',
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
         // Consider using JobOpportunityResource
        return response()->json($job);
    }

    /**
     * Remove the specified resource from storage. (Admin Only?)
     */
    public function destroy($id) // Route model binding: JobOpportunity $jobOpportunity
    {
         // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        $job = JobOpportunity::findOrFail($id);
        $job->delete(); 

        return response()->json(null, 204);
    }
}