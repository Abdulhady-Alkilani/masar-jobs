<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Apply middleware for authentication and graduate role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isGraduate'*/]);
    }

    /**
     * Display a listing of the graduate's enrollments.
     */
    public function index()
    {
        $user = Auth::user();
        $enrollments = Enrollment::where('UserID', $user->UserID)
            ->with('trainingCourse') // Eager load course details
            ->latest('Date') // Or created_at
            ->paginate(15);

        return view('graduate.enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource (Enrolling in a course).
     * Usually initiated from the TrainingCourse show page.
     */
    public function create()
    {
        return redirect()->route('courses.index')->with('info', 'Please find a course to enroll in first.');
    }

    /**
     * Store a newly created resource in storage (Enroll in a course).
     * Often triggered from a form/button on the TrainingCourse show page.
     */
    public function store(Request $request, TrainingCourse $trainingCourse = null)
    {
        // If TrainingCourse is not passed via route model binding, get it from request
        if (!$trainingCourse && $request->filled('course_id')) {
             $trainingCourse = TrainingCourse::findOrFail($request->input('course_id'));
        } elseif (!$trainingCourse) {
             return back()->with('error', 'Training course not specified.');
        }

        $user = Auth::user();

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('UserID', $user->UserID)
                                        ->where('CourseID', $trainingCourse->CourseID)
                                        ->exists();
        if ($existingEnrollment) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        // Check if course is still available / accepting enrollments (add status/dates logic if needed)
        // if ($trainingCourse->Status !== 'Active' || ...)

        // No specific validation needed usually, maybe payment details if applicable


        Enrollment::create([
            'UserID' => $user->UserID,
            'CourseID' => $trainingCourse->CourseID,
            'Status' => 'قيد التقدم', // Initial status
            'Date' => now(),
            'Complet Date' => null, // Not completed yet
        ]);

        // Optionally notify course creator/manager

        return redirect()->route('graduate.enrollments.index')->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Display the specified resource (view a specific enrollment).
     */
    public function show(Enrollment $enrollment) // Route Model Binding
    {
        // TODO: Add Authorization (Ensure this enrollment belongs to the logged-in user)
        if ($enrollment->UserID !== Auth::id()) {
             abort(403);
        }

        $enrollment->load('trainingCourse.creator'); // Load related data
        return view('graduate.enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     * Graduates usually cannot edit enrollments. Maybe allow cancellation?
     */
    public function edit(Enrollment $enrollment)
    {
        abort(404); // Or implement cancellation logic
    }

    /**
     * Update the specified resource in storage.
     * Graduates usually cannot edit enrollments. Status updated by system/admin.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage (Cancel enrollment).
     */
    public function destroy(Enrollment $enrollment)
    {
         // TODO: Add Authorization (Ensure this enrollment belongs to the logged-in user)
        if ($enrollment->UserID !== Auth::id()) {
            abort(403);
        }

        // Optional: Check if cancellation is allowed based on status/time
        if ($enrollment->Status === 'مكتمل') { // Example: Cannot cancel completed course
            return back()->with('error', 'Cannot cancel a completed course enrollment.');
        }

        $enrollment->delete(); // Or update status to 'ملغي' (Cancelled) instead of deleting

        // Optionally notify course creator/manager

        return redirect()->route('graduate.enrollments.index')->with('success', 'Enrollment cancelled successfully.');
    }
}