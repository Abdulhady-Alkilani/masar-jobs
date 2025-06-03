<?php

namespace App\Http\Controllers\Consultant;

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
     * Apply middleware for authentication and consultant role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isConsultant'*/]);
    }

    /**
     * Display the consultant's profile.
     */
    public function show()
    {
        $user = Auth::user()->load(['profile', 'skills']);
        if (!$user->profile) {
             Profile::create(['UserID' => $user->UserID]);
             $user->refresh()->load('profile');
        }
        return view('consultant.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the consultant's profile.
     */
    public function edit()
    {
        $user = Auth::user()->load('profile');
        if (!$user->profile) {
             Profile::create(['UserID' => $user->UserID]);
             $user->refresh()->load('profile');
        }
        $skills = Skill::orderBy('Name')->get();
        $userSkills = $user->skills->pluck('SkillID')->toArray();

        return view('consultant.profile.edit', compact('user', 'skills', 'userSkills'));
    }

    /**
     * Update the consultant's profile in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // TODO: Use Form Request Validation
        $validatedUserData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        $validatedProfileData = $request->validate([
            'University' => 'nullable|string|max:255', // Or 'Experience Summary' etc.
            'GPA' => 'nullable|string|max:255', // Or 'Years of Experience'
            'Personal Description' => 'nullable|string|max:2000',
            'Technical Description' => 'nullable|string|max:2000', // Expertise areas
            'Git Hyper Link' => 'nullable|url|max:2048', // Or LinkedIn profile etc.
        ]);

         if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            print($request);
             // TODO: Delete old photo
             $validatedUserData['photo'] = $request->file('photo')->store('user_photos', 'public');
         }

        $user->update($validatedUserData);

        if ($profile) {
            $profile->update($validatedProfileData);
        } else {
             $validatedProfileData['UserID'] = $user->UserID;
             Profile::create($validatedProfileData);
        }

         // --- Sync Skills ---
         $validatedSkills = $request->validate([
             'skills' => 'nullable|array',
             'skills.*' => 'required|string|in:مبتدئ,متوسط,متقدم',
         ]);

         $skillsToSync = [];
         if (!empty($validatedSkills['skills'])) {
             foreach ($validatedSkills['skills'] as $skillId => $level) {
                 if (Skill::where('SkillID', $skillId)->exists()) {
                     $skillsToSync[$skillId] = ['Stage' => $level];
                 }
             }
         }
         $user->skills()->sync($skillsToSync);


        return redirect()->route('consultant.profile.show')->with('success', 'Profile updated successfully.');
    }
}