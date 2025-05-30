<?php

namespace App\Http\Controllers\API\V1\Company; // Correct Namespace

use App\Http\Controllers\Controller;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagedTrainingCourseController extends Controller
{
     // --- Assuming Company Managers can manage courses ---
     // Logic is very similar to ManagedJobOpportunityController
     // Replace JobOpportunity with TrainingCourse and adjust fields/validation

    /**
     * Display a listing of the resource managed by the company manager.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        // TODO: Add Authorization check - Ensure user is a Company Manager (or relevant role)
        if ($user->type !== 'مدير شركة') { // Adjust role if needed
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $courses = TrainingCourse::where('UserID', $user->UserID)
                                 ->latest()
                                 ->paginate(15);

        // Consider TrainingCourseResourceCollection
        return response()->json($courses);
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
             // UserID is automatically set to the manager/creator
            'Course name' => 'required|string|max:255',
            'Trainers name' => 'nullable|string|max:255',
            'Course Description' => 'nullable|string',
            'Site' => 'nullable|string|max:100',
            'Trainers Site' => 'nullable|string|max:255',
            'Start Date' => 'nullable|date',
            'End Date' => 'nullable|date|after_or_equal:Start Date',
            'Enroll Hyper Link' => 'nullable|url|max:255',
            'Stage' => 'nullable|string|max:100',
            'Certificate' => 'nullable|string|max:50',
        ]);

        $validatedData['UserID'] = $user->UserID;
        $course = TrainingCourse::create($validatedData);

        // Consider TrainingCourseResource
        return response()->json($course, 201);
    }

    /**
     * Display the specified resource managed by the company manager.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $course = TrainingCourse::findOrFail($id);

        // Authorization: Check if the course belongs to the manager
        if ($course->UserID !== $user->UserID || $user->type !== 'مدير شركة') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Consider TrainingCourseResource
        return response()->json($course);
    }

    /**
     * Update the specified resource in storage managed by the company manager.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $course = TrainingCourse::findOrFail($id);

        // Authorization: Check if the course belongs to the manager
         if ($course->UserID !== $user->UserID || $user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

         $validatedData = $request->validate([
            // Manager cannot change UserID
            'Course name' => 'sometimes|required|string|max:255',
            'Trainers name' => 'nullable|string|max:255',
            'Course Description' => 'nullable|string',
            'Site' => 'nullable|string|max:100',
            'Trainers Site' => 'nullable|string|max:255',
            'Start Date' => 'nullable|date',
            'End Date' => 'nullable|date|after_or_equal:Start Date',
            'Enroll Hyper Link' => 'nullable|url|max:255',
            'Stage' => 'nullable|string|max:100',
            'Certificate' => 'nullable|string|max:50',
        ]);

        $course->update($validatedData);
        // Consider TrainingCourseResource
        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage managed by the company manager.
     */
    public function destroy(Request $request, $id)
    {
         $user = $request->user();
         $course = TrainingCourse::findOrFail($id);

        // Authorization: Check if the course belongs to the manager
         if ($course->UserID !== $user->UserID || $user->type !== 'مدير شركة') {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

        // TODO: Consider what happens to enrollments for this course (cascade delete?)
        $course->delete();

        return response()->json(null, 204);
    }
}