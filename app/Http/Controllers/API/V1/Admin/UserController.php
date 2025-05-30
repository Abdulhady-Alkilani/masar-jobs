<?php

namespace App\Http\Controllers\API\V1\Admin; // Correct Namespace

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
     /**
      * Instantiate a new controller instance.
      * Apply admin check middleware to all methods.
      */
    // public function __construct()
    // {
    //     // TODO: Apply middleware to ensure only admins can access these routes
    //     // $this->middleware('isAdmin'); // Assuming you create an 'isAdmin' middleware
    // }

    /**
     * Display a listing of the resource. (Admin Only)
     */
    public function index(Request $request)
    {
         // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        // TODO: Add filtering by type, status; sorting; pagination; search
        $users = User::with(['profile', 'company']) // Eager load relations
                     ->paginate(20);

        // Consider UserResourceCollection
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage. (Admin Only)
     */
    public function store(Request $request)
    {
         // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', Password::defaults()], // No confirmation needed?
            'phone' => 'nullable|string|max:20',
            'type' => 'required|string|in:خريج,خبير استشاري,مدير شركة,Admin',
            'status' => 'required|string|in:مفعل,معلق,محذوف',
            'email_verified' => 'sometimes|boolean',
            // 'photo' => 'nullable|string|max:255', // Handle upload
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'type' => $validatedData['type'],
            'status' => $validatedData['status'],
            'email_verified' => $validatedData['email_verified'] ?? false,
        ]);

        // Consider creating profile/company based on type if needed

        // Consider UserResource
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource. (Admin Only)
     */
    public function show($id) // Route model binding: User $user
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        $user = User::with(['profile', 'company', 'skills', 'jobApplications', 'enrollments'])->findOrFail($id);
        // Consider UserResource
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage. (Admin Only)
     */
    public function update(Request $request, $id) // Route model binding: User $user
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->UserID . ',UserID',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->UserID . ',UserID',
            'password' => ['nullable', Password::defaults()], // Optional password update
            'phone' => 'nullable|string|max:20',
            'type' => 'sometimes|required|string|in:خريج,خبير استشاري,مدير شركة,Admin',
            'status' => 'sometimes|required|string|in:مفعل,معلق,محذوف',
            'email_verified' => 'sometimes|boolean',
            // 'photo' => 'nullable|string|max:255',
        ]);

        // Hash password only if provided
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); // Don't update password if empty
        }

        $user->update($validatedData);

        // Consider UserResource
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage. (Admin Only)
     */
    public function destroy($id) // Route model binding: User $user
    {
         // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves?
        if ($user->UserID === Auth::id()) {
             return response()->json(['message' => 'Cannot delete the currently authenticated admin user.'], 403);
        }

        // TODO: Decide on cascading deletes for related records (profile, company, articles, jobs, applications, etc.)
        // This might be handled by foreign key constraints (`onDelete('cascade')`) or manually here.
        $user->tokens()->delete(); // Delete API tokens
        $user->delete();

        return response()->json(null, 204);
    }
}