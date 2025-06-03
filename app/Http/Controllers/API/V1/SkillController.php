<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource. (Public/Admin)
     * Used for searching/selection by users/admins.
     */
    public function index(Request $request)
    {
        $query = Skill::query();
        // Add search functionality
        if ($request->has('search')) {
            $query->where('Name', 'like', '%' . $request->input('search') . '%');
        }
        $skills = $query->orderBy('Name')->get(); // Get all matching, ordered

        // Consider SkillResourceCollection
        return response()->json($skills);
    }

    /**
     * Store a newly created resource in storage. (Admin Only)
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $validatedData = $request->validate([
            'Name' => 'required|string|max:255|unique:skills,Name',
        ]);

        $skill = Skill::create($validatedData);
        // Consider SkillResource
        return response()->json($skill, 201);
    }

    /**
     * Display the specified resource. (Admin Only?)
     */
    public function show($id) // Route model binding: Skill $skill
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only?
        $skill = Skill::findOrFail($id);
        // Consider SkillResource
        return response()->json($skill);
    }

    /**
     * Update the specified resource in storage. (Admin Only)
     */
    public function update(Request $request, $id) // Route model binding: Skill $skill
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $skill = Skill::findOrFail($id);

        $validatedData = $request->validate([
             'Name' => 'sometimes|required|string|max:255|unique:skills,Name,' . $skill->SkillID . ',SkillID',
        ]);

        $skill->update($validatedData);
        // Consider SkillResource
        return response()->json($skill);
    }

    /**
     * Remove the specified resource from storage. (Admin Only)
     */
    public function destroy($id) // Route model binding: Skill $skill
    {
         // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $skill = Skill::findOrFail($id);
        // TODO: Consider what happens to user_skills pivot records (cascade delete?)
        $skill->delete();

        return response()->json(null, 204);
    }
}