<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TrainingCourse; // Needed? Maybe just for filtering
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentViewerController extends Controller
{
     /**
     * Apply middleware for authentication and company manager role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isCompanyManager'*/]); // Or 'canManageCourses' if different permission
    }

    /**
     * Display a listing of enrollments for the manager's company courses.
     */
    public function index(Request $request)
    {
        $manager = Auth::user();

        // Get Course IDs posted by this manager
        $managerCourseIds = TrainingCourse::where('UserID', $manager->UserID)->pluck('CourseID');

        // Query enrollments for those courses
        $query = Enrollment::whereIn('CourseID', $managerCourseIds)
                           ->with(['user', 'trainingCourse']); // Load student and course details

        // Filtering Example: By Course
        if ($request->filled('course_id')) {
             $query->where('CourseID', $request->input('course_id'));
        }

        // Filtering Example: By Status
        if ($request->filled('status')) {
             $query->where('Status', $request->input('status'));
        }

        $enrollments = $query->latest('Date')->paginate(20);

        // Get list of manager's courses for the filter dropdown
        $managerCourses = TrainingCourse::where('UserID', $manager->UserID)
                                       ->orderBy('Course name')
                                       ->get(['CourseID', 'Course name']);

        return view('company_manager.enrollments.index', compact('enrollments', 'managerCourses'));
    }


    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment) // Route Model Binding
    {
        // TODO: Add Authorization - Ensure the enrollment belongs to a course owned by this manager
        $manager = Auth::user();
        $course = $enrollment->trainingCourse; // Get the related course

        if (!$course || $course->UserID !== $manager->UserID) {
            abort(403, 'You do not have permission to view this enrollment.');
        }

        $enrollment->load(['user.profile', 'trainingCourse']); // Load details
        return view('company_manager.enrollments.show', compact('enrollment'));
    }

     /**
     * Update the specified resource in storage (e.g., mark as complete).
     * Might be better handled by system logic or Admin.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
         // TODO: Add Authorization - Ensure manager owns the related course
        $manager = Auth::user();
        $course = $enrollment->trainingCourse;

        if (!$course || $course->UserID !== $manager->UserID) {
            abort(403, 'You do not have permission to update this enrollment.');
        }

        // Allow manager to mark as complete? Or just view?
        // TODO: Use Form Request Validation if updating
        $validatedData = $request->validate([
            'Status' => 'required|string|in:قيد التقدم,مكتمل,ملغي', // Define statuses
            'Complet Date' => 'nullable|date|required_if:Status,مكتمل',
        ]);

         // Update completion date only if status is 'مكتمل'
        if ($validatedData['Status'] !== 'مكتمل') {
            $validatedData['Complet Date'] = null;
        } elseif (empty($validatedData['Complet Date'])) {
             $validatedData['Complet Date'] = now(); // Default completion date to now if marked complete
        }


        $enrollment->update($validatedData);

         // Optionally notify the student

        return redirect()->route('company-manager.course-enrollments.show', $enrollment)
                         ->with('success', 'Enrollment status updated.');
    }

    // Managers don't create/edit/delete enrollments directly.
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(Enrollment $enrollment) { abort(404); }
    public function destroy(Enrollment $enrollment) { abort(404); }

}