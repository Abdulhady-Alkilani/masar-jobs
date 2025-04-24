<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentViewerController extends Controller
{
     /**
     * Apply middleware for authentication and consultant role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isConsultant'*/]);
    }

    /**
     * Display a listing of enrollments for the consultant's courses.
     */
    public function index(Request $request)
    {
        $consultant = Auth::user();
        $consultantCourseIds = TrainingCourse::where('UserID', $consultant->UserID)->pluck('CourseID');

        $query = Enrollment::whereIn('CourseID', $consultantCourseIds)
                           ->with(['user', 'trainingCourse']);

        if ($request->filled('course_id')) {
             $query->where('CourseID', $request->input('course_id'));
        }
        if ($request->filled('status')) {
             $query->where('Status', $request->input('status'));
        }

        $enrollments = $query->latest('Date')->paginate(20);
        $consultantCourses = TrainingCourse::where('UserID', $consultant->UserID)
                                       ->orderBy('Course name')
                                       ->get(['CourseID', 'Course name']);

        return view('consultant.enrollments.index', compact('enrollments', 'consultantCourses'));
    }


    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        // TODO: Add Authorization - Ensure enrollment belongs to consultant's course
        $consultant = Auth::user();
        $course = $enrollment->trainingCourse;

        if (!$course || $course->UserID !== $consultant->UserID) {
            abort(403);
        }

        $enrollment->load(['user.profile', 'trainingCourse']);
        return view('consultant.enrollments.show', compact('enrollment'));
    }

    // Consultants usually view, maybe don't update/delete enrollments.
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(Enrollment $enrollment) { abort(404); }
    public function update(Request $request, Enrollment $enrollment) { abort(404); }
    public function destroy(Enrollment $enrollment) { abort(404); }
}