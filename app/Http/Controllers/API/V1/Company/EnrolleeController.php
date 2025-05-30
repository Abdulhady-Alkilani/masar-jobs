<?php

namespace App\Http\Controllers\API\V1\Company; // Correct Namespace

use App\Http\Controllers\Controller;
use App\Models\TrainingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrolleeController extends Controller
{
    /**
     * Display a listing of enrollees for a specific training course managed by the user (Manager/Consultant?).
     * Route: GET /company-manager/courses/{training_course}/enrollees
     * Route: GET /consultant/courses/{training_course}/enrollees (If consultant manages courses)
     */
    public function index(Request $request, TrainingCourse $training_course) // Route model binding
    {
        $user = $request->user();

        // Authorization: Check if the course belongs to the authenticated user
        // This needs to work for both Managers and Consultants if they both manage courses
        if ($training_course->UserID !== $user->UserID || !in_array($user->type, ['مدير شركة', 'خبير استشاري'])) {
             return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Eager load enrollee user details and their profile
        $enrollees = $training_course->enrollments()
                                    ->with(['user' => function ($query) {
                                        $query->select('UserID', 'first_name', 'last_name', 'email', 'phone')
                                              ->with('profile');
                                    }])
                                    ->orderBy('Date') // Order by enrollment date
                                    ->get();

        // Consider using a combined EnrolleeResource or EnrollmentResource collection
        return response()->json($enrollees);

        // Similar transformation as in ApplicantController could be applied
    }

     // TODO: Add methods to change enrollment status (e.g., mark as complete, cancel)
}