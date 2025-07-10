<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile; // Assuming you have a Profile model
use App\Models\User; // We will mostly work with the User model directly now
use App\Models\Skill; // Needed for syncSkills (although keeping it separate)
use Illuminate\Support\Arr; // For array manipulation
use Illuminate\Support\Facades\Storage; // For handling user photo file
use Illuminate\Support\Facades\DB; // <-- Add this line

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile and related data.
     * Route: GET /api/v1/profile
     */
    public function show(Request $request)
    {
        // Get the authenticated user and eager load necessary relations
        $user = $request->user()->load([
             'profile', // Load the Profile model (HasOne)
             'skills',  // Load the skills (BelongsToMany via pivot)
             'company'  // Load the company if user is a manager (HasOne)
             // Add other relations you want to include here, e.g., 'jobApplications', 'enrollments'
        ]);

        // Ensure the profile exists (create if not, although AuthController@register should do this)
        // This firstOrCreate call might be redundant if registration always creates profile,
        // but it acts as a safeguard.
        if (!$user->profile) {
            $user->profile()->firstOrCreate(['UserID' => $user->UserID]);
            $user->load('profile'); // Reload user to get the new profile relationship
        }


        // Consider using a UserResource to format the output consistently,
        // including formatting file URLs (user photo, maybe company media if loaded)
        // and flattening profile/skills data if needed.
        return response()->json($user); // Return the User object with loaded relations
    }

    /**
     * Update the authenticated user's profile and basic user information.
     * Route: PUT /api/v1/profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Ensure profile exists or create it
        $profile = $user->profile()->firstOrCreate(['UserID' => $user->UserID]);

        // --- Validation ---
        // Validate fields for BOTH User and Profile models
        $validatedData = $request->validate([
            // User fields that user can update
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            // 'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->UserID . ',UserID', // Username update might require special logic/permissions
            // 'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->UserID . ',UserID', // Email update might require verification
            'phone' => 'nullable|string|max:20',
             // User photo file upload/removal indicator
             'photo' => 'nullable|image|max:2048', // Accepts image files, max 2MB. Nullable allows removing existing.

            // Profile fields
            'University' => 'nullable|string|max:255',
            'GPA' => 'nullable|string|max:10', // Or numeric validation
            'Personal Description' => 'nullable|string',
            'Technical Description' => 'nullable|string',
            'Git Hyper Link' => 'nullable|url|max:255',
            // Fields like 'type', 'status', 'email_verified' should NOT be updateable by the user here
        ]);
        // --- End Validation ---


        // --- Separate data for User and Profile ---
        $userUpdateData = Arr::only($validatedData, [
            'first_name',
            'last_name',
            'phone',
            // 'username', // If allowed
            // 'email',    // If allowed and handled with verification
        ]);

        $profileUpdateData = Arr::only($validatedData, [
            'University',
            'GPA',
            'Personal Description',
            'Technical Description',
            'Git Hyper Link',
        ]);
        // --- End Separation ---


        // --- Handle User Photo File ---
        // The 'photo' field is on the User model
         if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($user->photo) {
                 Storage::disk('public')->delete($user->photo);
            }
            // Store the new photo
            $userUpdateData['photo'] = $request->file('photo')->store('users/photos', 'public');
         } elseif ($request->has('photo') && is_null($request->file('photo'))) {
             // If client sent 'photo': null, delete old photo and set column to null
             if ($user->photo) {
                 Storage::disk('public')->delete($user->photo);
             }
             $userUpdateData['photo'] = null;
         }
         // If 'photo' key is not in $userUpdateData, it means the client didn't send it,
         // so we don't unset it from $validatedData to avoid updating the column.
         // The unset logic from previous controllers isn't strictly needed here
         // if we are careful with Arr::only and how we build $userUpdateData.


        // --- Update Models ---
        DB::beginTransaction(); // Use transaction for atomicity

        try {
             // Update User model
             if (!empty($userUpdateData)) {
                 $user->update($userUpdateData);
             }

            // Update Profile model
             if (!empty($profileUpdateData)) {
                 $profile->update($profileUpdateData);
             }

             // TODO: Send a confirmation notification to the user?

            DB::commit(); // Apply changes

             // Reload the user with relations to return the complete updated data
            $user->load([
                'profile',
                'skills',
                'company',
                // Add other relations loaded in show()
            ]);

            // Consider using a UserResource
            return response()->json($user); // Return the updated User object

        } catch (\Exception $e) {
            DB::rollBack(); // Revert changes

            // TODO: If file upload succeeded but DB failed, delete the uploaded file on rollback.
            // This is tricky. Simplest might be to rely on a separate cleanup process for orphaned files.
             if (isset($userUpdateData['photo']) && $userUpdateData['photo']) {
                 // Check if the *newly uploaded* file exists and delete it
                 if (Storage::disk('public')->exists($userUpdateData['photo'])) {
                     Storage::disk('public')->delete($userUpdateData['photo']);
                 }
             }


             \Log::error("Error updating user profile for UserID {$user->UserID}: {$e->getMessage()}");
            return response()->json(['message' => 'Failed to update profile due to a server error.', 'error' => $e->getMessage()], 500);
        }
        // --- End Update Models ---
    }

     /**
      * Sync the skills for the authenticated user.
      * Route: POST /api/v1/profile/skills (Keeping this separate)
      */
     public function syncSkills(Request $request)
     {
         $user = $request->user();

         // Expects an array of skill IDs, potentially with pivot data like 'Stage'
         $validatedData = $request->validate([
             'skills' => 'required|array',
             'skills.*' => 'sometimes|integer|exists:skills,SkillID', // If simple array of IDs
             // Or more complex validation if associative array with pivot data
             // 'skills.*.Stage' => 'nullable|string|in:مبتدئ,متوسط,متقدم'
         ]);

         $skillsToSync = [];
         if (isset($validatedData['skills']) && is_array($validatedData['skills'])) {
             if (!empty($validatedData['skills']) && array_is_list($validatedData['skills'])) {
                // Simple array [1, 2, 3]
                foreach ($validatedData['skills'] as $skillId) {
                     if (Skill::find($skillId)) { // Ensure skill exists
                         $skillsToSync[$skillId] = []; // Default pivot data (e.g., no stage)
                     }
                 }
             } else {
                 // Associative array [1 => ['Stage' => '...'], 2 => []]
                 foreach ($validatedData['skills'] as $skillId => $pivotData) {
                     if (Skill::find($skillId)) { // Ensure skill exists
                         $stage = isset($pivotData['Stage']) ? $pivotData['Stage'] : null;
                         // Validate stage if provided
                         if ($stage && !in_array($stage, ['مبتدئ', 'متوسط', 'متقدم'])) {
                            // Log warning or return error for invalid stage
                            continue; // Skip invalid stage entry
                         }
                         $skillsToSync[$skillId] = ['Stage' => $stage];
                     }
                 }
             }
         }

         // Use transaction for atomicity
         DB::beginTransaction();
         try {
            $user->skills()->sync($skillsToSync);
            DB::commit();
         } catch (\Exception $e) {
            DB::rollBack();
             \Log::error("Error syncing skills for UserID {$user->UserID}: {$e->getMessage()}");
            return response()->json(['message' => 'Failed to update skills due to a server error.', 'error' => $e->getMessage()], 500);
         }


         // Return updated user with skills
         // Reload user with skills relationship to ensure latest pivot data is included
         $user->load('skills');
         return response()->json($user); // Consider UserResource
     }

     // Note: Password update should be a separate dedicated endpoint (e.g. PUT /api/v1/user/password)
     //       User status/type/email_verified should NOT be updateable by the user via this endpoint.
}