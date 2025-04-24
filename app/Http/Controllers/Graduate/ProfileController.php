<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
     /**
     * Apply middleware for authentication and graduate role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isGraduate'*/]);
    }

    /**
     * Display the graduate's profile.
     * (Using show method as it's a single resource per user)
     */
    public function show() // No parameter needed, gets authenticated user
    {
        $user = Auth::user()->load(['profile', 'skills']); // Eager load profile and skills
        // If profile might not exist yet, handle that case
        if (!$user->profile) {
            // Optionally create a default profile or redirect to edit/create form
             Profile::create(['UserID' => $user->UserID]); // Create basic profile if missing
             $user->refresh()->load('profile'); // Refresh user model to get the new profile
             // return redirect()->route('graduate.profile.edit')->with('info', 'Please complete your profile.');
        }
        return view('graduate.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the graduate's profile.
     */
    public function edit() // No parameter needed
    {
        $user = Auth::user()->load('profile');
        if (!$user->profile) {
             Profile::create(['UserID' => $user->UserID]);
             $user->refresh()->load('profile');
        }
        $skills = Skill::orderBy('Name')->get(); // Get all available skills
        $userSkills = $user->skills->pluck('SkillID')->toArray(); // Get IDs of user's current skills

        return view('graduate.profile.edit', compact('user', 'skills', 'userSkills'));
    }

    /**
     * Update the graduate's profile in storage.
     */
    public function update(Request $request) // No parameter needed
    {
        $user = Auth::user();
        $profile = $user->profile;

        // TODO: Use Form Request Validation
        $validatedUserData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Cannot change username/email easily here, handle separately if needed
            'phone' => 'nullable|string|max:20',
            // Add photo upload validation if allowing profile picture change
             'photo' => 'nullable|image|max:2048',
        ]);

        $validatedProfileData = $request->validate([
            'University' => 'nullable|string|max:255',
            'GPA' => 'nullable|numeric|min:0|max:5', // Adjust max GPA if needed
            'Personal Description' => 'nullable|string|max:2000',
            'Technical Description' => 'nullable|string|max:2000',
            'Git Hyper Link' => 'nullable|url|max:2048',
        ]);

         // Handle photo upload
         if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
             // TODO: Delete old photo if exists
             // if ($user->photo) { Storage::disk('public')->delete($user->photo); }
             $validatedUserData['photo'] = $request->file('photo')->store('user_photos', 'public');
         }


        // Update user basic info
        $user->update($validatedUserData);

        // Update profile info
        if ($profile) {
            $profile->update($validatedProfileData);
        } else {
             // Create profile if it somehow still doesn't exist
             $validatedProfileData['UserID'] = $user->UserID;
             Profile::create($validatedProfileData);
        }

         // --- Sync Skills ---
         $validatedSkills = $request->validate([
             // Assumes skills are submitted as an array of Skill IDs with levels
             // Example: skills[skill_id] = level
             'skills' => 'nullable|array',
             'skills.*' => 'required|string|in:مبتدئ,متوسط,متقدم', // Validate the level
         ]);

         $skillsToSync = [];
         if (!empty($validatedSkills['skills'])) {
             foreach ($validatedSkills['skills'] as $skillId => $level) {
                 // Ensure the skill ID actually exists
                 if (Skill::where('SkillID', $skillId)->exists()) {
                     $skillsToSync[$skillId] = ['Stage' => $level];
                 }
             }
         }
         // Sync skills with pivot data (Stage)
         $user->skills()->sync($skillsToSync); // sync() handles adding, updating, removing


        return redirect()->route('graduate.profile.show')->with('success', 'Profile updated successfully.');
    }

    // Typically no create, store, index, destroy needed for a user's own profile
    // managed this way (show/edit/update).
}