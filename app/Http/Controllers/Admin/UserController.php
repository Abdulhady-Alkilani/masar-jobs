<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // For unique checks on update

class UserController extends Controller
{
    // TODO: Apply 'isAdmin' middleware in routes

    public function index()
    {
        // TODO: Add Authorization (Admin only)
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // TODO: Add Authorization (Admin only)
        return view('admin.users.create'); // Pass roles/types if needed
    }

    public function store(Request $request)
    {
        // TODO: Add Authorization (Admin only)
        // TODO: Form Request Validation
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'type' => ['required', 'string', Rule::in(['خريج', 'خبير استشاري', 'مدير شركة', 'Admin'])],
            'status' => ['required', 'string', Rule::in(['مفعل', 'معلق', 'محذوف'])],
        ]);

        User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'type' => $validatedData['type'],
            'status' => $validatedData['status'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user) // Route Model Binding
    {
        // TODO: Add Authorization (Admin only)
        // Maybe load profile, company etc. -> $user->load(['profile', 'company']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
       // TODO: Add Authorization (Admin only)
        return view('admin.users.edit', compact('user')); // Pass roles/types/statuses
    }

    public function update(Request $request, User $user)
    {
        // TODO: Add Authorization (Admin only)
        // TODO: Form Request Validation
         $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users','username')->ignore($user->UserID, 'UserID')], // Check unique username, ignore self
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users','email')->ignore($user->UserID, 'UserID')], // Check unique email, ignore self
            'password' => 'nullable|string|min:8|confirmed', // Optional password update
            'phone' => 'nullable|string|max:20',
            'type' => ['required', 'string', Rule::in(['خريج', 'خبير استشاري', 'مدير شركة', 'Admin'])],
            'status' => ['required', 'string', Rule::in(['مفعل', 'معلق', 'محذوف'])],
        ]);

        // Only update password if provided
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); // Don't update password if empty
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // TODO: Add Authorization (Admin only)
        // Consider soft deletes if you have that trait on the User model
        // Prevent admin from deleting themselves?
        if (Auth::id() === $user->UserID) {
             return back()->with('error', 'You cannot delete your own account.');
        }

        // Handle related data (posts, profile, etc.) based on foreign key constraints (cascade, set null)
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}