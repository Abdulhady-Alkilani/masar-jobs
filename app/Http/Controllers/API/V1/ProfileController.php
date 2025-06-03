<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile; // Assuming you have a Profile model
use App\Models\Skill;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        // Eager load profile, create if not exists
        $profile = $user->profile()->firstOrCreate(
            ['UserID' => $user->UserID],
            [] // Default values if needed on creation
        );

        // Consider using ProfileResource which includes user data if needed
        return response()->json($profile);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile()->firstOrCreate(['UserID' => $user->UserID]);

        $validatedData = $request->validate([
            'University' => 'nullable|string|max:255',
            'GPA' => 'nullable|string|max:10', // Or numeric validation
            'Personal Description' => 'nullable|string',
            'Technical Description' => 'nullable|string',
            'Git Hyper Link' => 'nullable|url|max:255',
            // User fields can be updated via a separate /user endpoint or here?
            // 'first_name' => 'sometimes|required|string|max:255',
            // 'last_name' => 'sometimes|required|string|max:255',
            // 'phone' => 'nullable|string|max:20',
        ]);

        // Update profile fields
        $profile->update($validatedData);

        // Update user fields if included in validation
        // $userUpdateData = Arr::only($validatedData, ['first_name', 'last_name', 'phone']);
        // if (!empty($userUpdateData)) {
        //     $user->update($userUpdateData);
        // }

        // Consider using ProfileResource
        return response()->json($profile->fresh()); // Return updated profile
    }

     /**
      * Sync the skills for the authenticated user.
      */
     public function syncSkills(Request $request)
     {
         $user = $request->user();

         // Expects an array of skill IDs, potentially with pivot data like 'Stage'
         // Example payload: { "skills": [1, 5, 10] }
         // Or with stage: { "skills": { "1": {"Stage": "متقدم"}, "5": {"Stage": "متوسط"} } }
         $validatedData = $request->validate([
             'skills' => 'required|array',
             // Validate that keys/values are existing SkillIDs if it's an associative array
             'skills.*' => 'sometimes|integer|exists:skills,SkillID', // If simple array of IDs
             // Or more complex validation if associative array with pivot data
             // 'skills.*.Stage' => 'nullable|string|in:مبتدئ,متوسط,متقدم'
         ]);

         // Use sync for simple array of IDs:
         // $user->skills()->sync($validatedData['skills']);

         // Use sync for associative array with pivot data:
         $skillsToSync = [];
         if (isset($validatedData['skills']) && is_array($validatedData['skills'])) {
             // Check if it's associative array (ID => PivotData) or simple array (ID)
             if (!empty($validatedData['skills']) && array_is_list($validatedData['skills'])) {
                // Simple array [1, 2, 3] - create structure for sync
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
                            continue; // Skip invalid stage
                         }
                         $skillsToSync[$skillId] = ['Stage' => $stage];
                     }
                 }
             }
         }

         $user->skills()->sync($skillsToSync);


         // Return updated user with skills
         return response()->json($user->load('skills')); // Consider UserResource
     }
}