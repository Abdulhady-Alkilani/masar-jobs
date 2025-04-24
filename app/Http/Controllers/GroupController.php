<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource. (Public)
     */
    public function index()
    {
        $groups = Group::latest()->paginate(20);
        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource. (Admin Only)
     */
    public function create()
    {
        // Handled by Admin/GroupController
        abort(404); // Or redirect if accessed directly
    }

    /**
     * Store a newly created resource in storage. (Admin Only)
     */
    public function store(Request $request)
    {
        // Handled by Admin/GroupController
         abort(404);
    }

    /**
     * Display the specified resource. (Public)
     * May not be needed if index just lists links.
     */
    public function show(Group $group)
    {
         // If you have a dedicated show page for a group
         // return view('groups.show', compact('group'));
         // Often, just clicking the link in the index is enough.
         return redirect($group->{'Telegram Hyper Link'}); // Redirect directly?
    }

    /**
     * Show the form for editing the specified resource. (Admin Only)
     */
    public function edit(Group $group)
    {
        // Handled by Admin/GroupController
         abort(404);
    }

    /**
     * Update the specified resource in storage. (Admin Only)
     */
    public function update(Request $request, Group $group)
    {
         // Handled by Admin/GroupController
          abort(404);
    }

    /**
     * Remove the specified resource from storage. (Admin Only)
     */
    public function destroy(Group $group)
    {
        // Handled by Admin/GroupController
         abort(404);
    }
}