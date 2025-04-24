<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkillController extends Controller
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
         $query = Skill::query();
         if ($request->filled('search')) {
             $query->where('Name', 'like', '%' . $request->input('search') . '%');
         }
         $skills = $query->orderBy('Name')->paginate(25);
         return view('admin.skills.index', compact('skills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.skills.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'Name' => 'required|string|max:255|unique:skills,Name',
        ]);

        Skill::create($validatedData);

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    /**
     * Display the specified resource. (Not usually needed for simple skills)
     */
    public function show(Skill $skill)
    {
        return view('admin.skills.show', compact('skill')); // Or redirect to index
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill)
    {
        return view('admin.skills.edit', compact('skill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            'Name' => ['required', 'string', 'max:255', Rule::unique('skills', 'Name')->ignore($skill->SkillID, 'SkillID')],
        ]);

        $skill->update($validatedData);

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        // Consider implications: What happens to users who have this skill?
        // Maybe prevent deletion if skill is in use, or cascade delete from user_skills?
        // $userCount = $skill->users()->count();
        // if ($userCount > 0) {
        //     return back()->with('error', "Cannot delete skill '{$skill->Name}' as it is assigned to {$userCount} users.");
        // }

        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }
}