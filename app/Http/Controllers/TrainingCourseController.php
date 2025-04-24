<?php

namespace App\Http\Controllers;

use App\Models\TrainingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingCourseController extends Controller
{
    /**
     * Display a listing of the resource. (Public)
     */
    public function index(Request $request)
    {
        // Add filtering/searching
        $query = TrainingCourse::query(); // Add conditions like status if needed

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('Course name', 'like', "%{$searchTerm}%")
                  ->orWhere('Course Description', 'like', "%{$searchTerm}%")
                  ->orWhere('Trainers name', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('stage') && in_array($request->input('stage'), ['مبتدئ','متوسط','متقدم'])) {
             $query->where('Stage', $request->input('stage'));
        }


        $trainingCourses = $query->latest()->paginate(15);
        return view('courses.index', compact('trainingCourses'));
    }

    /**
     * Show the form for creating a new resource.
     * Accessible by Manager, Consultant, Admin
     */
    public function create()
    {
        // TODO: Add Authorization (Policy or Gate for 'create course')
        if (!in_array(Auth::user()->type, ['مدير شركة', 'خبير استشاري', 'Admin'])) {
             abort(403, 'Unauthorized action.');
        }
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     * Accessible by Manager, Consultant, Admin
     */
    public function store(Request $request)
    {
         // TODO: Add Authorization
         if (!in_array(Auth::user()->type, ['مدير شركة', 'خبير استشاري', 'Admin'])) {
             abort(403, 'Unauthorized action.');
        }

        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'Course name' => 'required|string|max:255',
            'Trainers name' => 'nullable|string|max:255',
            'Course Description' => 'required|string',
            'Site' => 'required|string|max:100', // (حضوري, اونلاين) etc.
            'Trainers Site' => 'nullable|string|max:255',
            'Start Date' => 'nullable|date',
            'End Date' => 'nullable|date|after_or_equal:Start Date',
            'Enroll Hyper Link' => 'nullable|url|max:2048',
            'Stage' => 'required|string|in:مبتدئ,متوسط,متقدم',
            'Certificate' => 'required|string|in:يوجد,لا يوجد', // Or boolean
        ]);

        TrainingCourse::create([
            'UserID' => Auth::id(), // Creator's UserID
            'Course name' => $validatedData['Course name'],
            'Trainers name' => $validatedData['Trainers name'],
            'Course Description' => $validatedData['Course Description'],
            'Site' => $validatedData['Site'],
            'Trainers Site' => $validatedData['Trainers Site'],
            'Start Date' => $validatedData['Start Date'],
            'End Date' => $validatedData['End Date'],
            'Enroll Hyper Link' => $validatedData['Enroll Hyper Link'],
            'Stage' => $validatedData['Stage'],
            'Certificate' => $validatedData['Certificate'],
        ]);

        return redirect()->route('courses.index')->with('success', 'Training course created successfully!'); // Or redirect to show
    }

    /**
     * Display the specified resource. (Public)
     */
    public function show(TrainingCourse $trainingCourse) // Route Model Binding
    {
        $trainingCourse->load('creator'); // Eager load creator info
        return view('courses.show', compact('trainingCourse'));
    }

    /**
     * Show the form for editing the specified resource.
     * Accessible by creator or Admin
     */
    public function edit(TrainingCourse $trainingCourse)
    {
        // TODO: Add Authorization (Policy: user can update course?)
        if (Auth::id() !== $trainingCourse->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
         }
        return view('courses.edit', compact('trainingCourse'));
    }

    /**
     * Update the specified resource in storage.
     * Accessible by creator or Admin
     */
    public function update(Request $request, TrainingCourse $trainingCourse)
    {
        // TODO: Add Authorization
        if (Auth::id() !== $trainingCourse->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
         }

        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
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

        return redirect()->route('courses.show', $trainingCourse)->with('success', 'Training course updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * Accessible by creator or Admin
     */
    public function destroy(TrainingCourse $trainingCourse)
    {
       // TODO: Add Authorization
       if (Auth::id() !== $trainingCourse->UserID && Auth::user()->type !== 'Admin') {
           abort(403, 'Unauthorized action.');
       }

       // Consider implications on enrollments (CASCADE constraint?)
       $trainingCourse->delete();

       // Redirect to relevant dashboard or index
       $redirectRoute = match(Auth::user()->type) {
           'Admin' => 'admin.training-courses.index',
           'مدير شركة' => 'company-manager.training-courses.index',
           'خبير استشاري' => 'consultant.training-courses.index',
           default => 'home', // Fallback
       };
       return redirect()->route($redirectRoute)->with('success', 'Training course deleted successfully!');
    }
}