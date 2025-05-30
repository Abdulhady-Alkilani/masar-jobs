<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;

class TrainingCourseController extends Controller
{
    /**
     * Display a listing of the resource. (Public)
     */
    public function index(Request $request)
    {
        // TODO: Add filtering (by stage, site?), sorting, pagination, search
        $courses = TrainingCourse::with('creator:UserID,first_name,last_name') // Eager load creator name
                                 ->latest()
                                 ->paginate(15);

        // Consider using TrainingCourseResourceCollection
        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage. (Admin Only?)
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy) - Only Admin?
         $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // Admin assigns user
            'Course name' => 'required|string|max:255',
            'Trainers name' => 'nullable|string|max:255',
            'Course Description' => 'nullable|string',
            'Site' => 'nullable|string|max:100',
            'Trainers Site' => 'nullable|string|max:255',
            'Start Date' => 'nullable|date',
            'End Date' => 'nullable|date|after_or_equal:Start Date',
            'Enroll Hyper Link' => 'nullable|url|max:255',
            'Stage' => 'nullable|string|max:100', // Consider specific values (in:...)
            'Certificate' => 'nullable|string|max:50', // Consider specific values (in:...) or boolean
        ]);

        $course = TrainingCourse::create($validatedData);
        // Consider using TrainingCourseResource
        return response()->json($course, 201);
    }

    /**
     * Display the specified resource. (Public)
     */
    public function show($id) // Route model binding: TrainingCourse $trainingCourse
    {
        $course = TrainingCourse::with('creator:UserID,first_name,last_name')->findOrFail($id);
        // Consider using TrainingCourseResource
        return response()->json($course);
    }

    /**
     * Update the specified resource in storage. (Admin Only?)
     */
    public function update(Request $request, $id) // Route model binding: TrainingCourse $trainingCourse
    {
         // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        $course = TrainingCourse::findOrFail($id);

        $validatedData = $request->validate([
            'UserID' => 'sometimes|required|exists:users,UserID',
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
        // Consider using TrainingCourseResource
        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage. (Admin Only?)
     */
    public function destroy($id) // Route model binding: TrainingCourse $trainingCourse
    {
        // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        $course = TrainingCourse::findOrFail($id);
        $course->delete();

        return response()->json(null, 204);
    }
}