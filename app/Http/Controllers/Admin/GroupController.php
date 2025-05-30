<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
     /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        // $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         // !!! التصحيح هنا: الترتيب حسب المفتاح الأساسي بدلاً من latest() !!!
         $groups = Group::orderBy('GroupID', 'desc')->paginate(20);
         // أو يمكنك عدم الترتيب إذا لم يكن مهمًا:
         // $groups = Group::paginate(20);

         return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Use Form Request Validation
        $validatedData = $request->validate([
            // Add name/description fields to Group model/table if needed
             // تأكد من تطابق اسم الحقل هنا مع قاعدة البيانات والمودل
            'Telegram Hyper Link' => 'required|url|max:2048|unique:groups,Telegram Hyper Link',
        ]);

        Group::create($validatedData);

        return redirect()->route('admin.groups.index')->with('success', 'Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
         return view('admin.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        // TODO: Use Form Request Validation
         // تأكد من تطابق اسم الحقل والمفتاح الأساسي هنا
        $validatedData = $request->validate([
             'Telegram Hyper Link' => ['required', 'url', 'max:2048', Rule::unique('groups', 'Telegram Hyper Link')->ignore($group->GroupID, 'GroupID')],
        ]);

        $group->update($validatedData);

        return redirect()->route('admin.groups.index')->with('success', 'Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', 'Group deleted successfully.');
    }
}