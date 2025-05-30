<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource. (Public/Admin)
     */
    public function index()
    {
        $groups = Group::all(); // Or paginate?
        // Consider GroupResourceCollection
        return response()->json($groups);
    }

    /**
     * Store a newly created resource in storage. (Admin Only)
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
         $validatedData = $request->validate([
            'Telegram Hyper Link' => 'required|url|max:255|unique:groups,Telegram Hyper Link',
            // Add other fields if needed (e.g., name, description)
        ]);

        $group = Group::create($validatedData);
        // Consider GroupResource
        return response()->json($group, 201);
    }

    /**
     * Display the specified resource. (Public/Admin)
     */
    public function show($id) // Route model binding: Group $group
    {
        $group = Group::findOrFail($id);
         // Consider GroupResource
        return response()->json($group);
    }

    /**
     * Update the specified resource in storage. (Admin Only)
     */
    public function update(Request $request, $id) // Route model binding: Group $group
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $group = Group::findOrFail($id);

        $validatedData = $request->validate([
            'Telegram Hyper Link' => 'sometimes|required|url|max:255|unique:groups,Telegram Hyper Link,' . $group->GroupID . ',GroupID',
             // Add other fields if needed
        ]);

        $group->update($validatedData);
        // Consider GroupResource
        return response()->json($group);
    }

    /**
     * Remove the specified resource from storage. (Admin Only)
     */
    public function destroy($id) // Route model binding: Group $group
    {
        // TODO: Add Authorization check (Gate/Policy) - Admin Only
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json(null, 204);
    }
}