<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingCourse;
use App\Models\User; // For selecting creator
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingCourseController extends Controller
{
    /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isAdmin'*/]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TrainingCourse::query()->with('creator'); // Load creator

        // Add admin filtering/searching
        if ($request->filled('search')) { }
        if ($request->filled('stage')) { }
        if ($request->filled('creator_id')) { }


        $trainingCourses = $query->latest()->paginate(20);
        return view('admin.training_courses.index', compact('trainingCourses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // Admin selects the creator (Manager, Consultant, Admin?)
        $creators = User::whereIn('type', ['مدير شركة', 'خبير استشاري', 'Admin'])->orderBy('username')->pluck('username', 'UserID');
        return view('admin.training_courses.create', compact('creators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Reuse validation from public controller, add UserID
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID',
            'Course name' => 'required|string|max:255',
            'Trainers name' => 'nullable|string|max:255',
            'Course Description' => 'required|string',
            'Site' => 'required|string|max:100',
            'Trainers Site' => 'nullable|string|max:255',
            'Start Date' => 'nullable|date',
            'End Date' => 'nullable|date|after_or_equal:Start Date',
            'Enroll Hyper Link' => 'nullable|url|max:2048',
            'Stage' => 'required|string|in:مبتدئ,متوسط,متقدم',
            'Certificate' => 'required|string|in:يوجد,لا يوجد',
            // Add status field if admin controls course visibility/status
        ]);

        TrainingCourse::create($validatedData);

        return redirect()->route('admin.training-courses.index')->with('success', 'Training Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingCourse $trainingCourse)
{
    // تحميل العلاقات اللازمة للعرض
    $trainingCourse->load(['creator', 'enrollments.user']); // مثال

    // !!! تأكد أنه يعيد الـ View الصحيح !!!
    return view('admin.training_courses.show', compact('trainingCourse'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingCourse $trainingCourse)
    {
        $creators = User::whereIn('type', ['مدير شركة', 'خبير استشاري', 'Admin'])->orderBy('username')->pluck('username', 'UserID');
        $trainingCourse->load('creator');
        return view('admin.training_courses.edit', compact('trainingCourse', 'creators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingCourse $trainingCourse)
    {
        // Reuse validation from public controller, add UserID
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID',
            'Course name' => 'required|string|max:255',
            'Trainers name' => 'nullable|string|max:255',
            'Course Description' => 'required|string',
            'Site' => 'required|string|max:100',
            'Trainers Site' => 'nullable|string|max:255',
            'Start Date' => 'nullable|date',
            'End Date' => 'nullable|date|after_or_equal:Start Date',
            'Enroll Hyper Link' => 'nullable|url|max:2048',
            'Stage' => 'required|string|in:مبتدئ,متوسط,متقدم',
            'Certificate' => 'required|string|in:يوجد,لا يوجد',
        ]);

        $trainingCourse->update($validatedData);

        return redirect()->route('admin.training-courses.show', $trainingCourse)->with('success', 'Training Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingCourse $trainingCourse)
    {
        // Reuse logic from public controller destroy method
        $trainingCourse->delete();
        return redirect()->route('admin.training-courses.index')->with('success', 'Training Course deleted successfully.');
    }
}