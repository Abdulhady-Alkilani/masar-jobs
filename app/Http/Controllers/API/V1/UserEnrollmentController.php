<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserEnrollmentController extends Controller
{
    /**
     * Display a listing of the authenticated user's course enrollments.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $enrollments = $user->enrollments()
                            ->with('trainingCourse:CourseID,Course name') // Eager load basic course info
                            ->latest('Date') // Order by enrollment date
                            ->get();

        // Consider using EnrollmentResourceCollection
        return response()->json($enrollments);
    }

    /**
     * Store a new course enrollment for the authenticated user.
     * Route: POST /courses/{training_course}/enroll
     */
    public function store(Request $request, TrainingCourse $training_course) // Route model binding
    {
        $user = $request->user();

        // Check if user already enrolled
        $existingEnrollment = Enrollment::where('UserID', $user->UserID)
                                        ->where('CourseID', $training_course->CourseID)
                                        ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'You are already enrolled in this course.'], 409); // Conflict
        }

         // Check if course enrollment is still open (optional, based on start date?)
        // if ($training_course->{'Start Date'} && Carbon::parse($training_course->{'Start Date'})->isPast()) {
        //      return response()->json(['message' => 'Enrollment for this course has closed.'], 400);
        // }


        // No extra data needed for basic enrollment based on schema
        // $validatedData = $request->validate([...]);

        $enrollment = Enrollment::create([
            'UserID' => $user->UserID,
            'CourseID' => $training_course->CourseID,
            'Status' => 'قيد التقدم', // Initial status
            'Date' => Carbon::now(),
            'Complet Date' => null,
        ]);

        // Consider using EnrollmentResource
        return response()->json($enrollment, 201);
    }

    /**
     * Remove the specified course enrollment of the authenticated user.
     * Route: DELETE /my-enrollments/{enrollment}
     */
    public function destroy(Request $request, Enrollment $enrollment) // Route model binding
    {
        $user = $request->user();

        // Authorization: Check if the enrollment belongs to the authenticated user
        if ($enrollment->UserID !== $user->UserID) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Optional: Check if enrollment can be cancelled (e.g., only if status is 'قيد التقدم')
        // if ($enrollment->Status !== 'قيد التقدم') {
        //     return response()->json(['message' => 'Cannot cancel enrollment with status: ' . $enrollment->Status], 400);
        // }

        $enrollment->delete();

        return response()->json(null, 204);
    }
}