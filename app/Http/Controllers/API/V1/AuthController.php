<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20', // Adjust validation as needed
            'type' => 'required|string|in:خريج,خبير استشاري,مدير شركة', // Only allow these types for self-registration?
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'type' => $validatedData['type'],
            'status' => 'مفعل', // Or 'pending' if admin approval needed for some types?
             // Add default email_verified status if needed
        ]);

        // Optionally create related profile/company based on type
         if ($user->type === 'خريج' || $user->type === 'خبير استشاري') {
             $user->profile()->create([]); // Create empty profile
         }
         // Consider company creation/request workflow for 'مدير شركة'

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('profile') // Return user data (consider UserResource)
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
            // Or return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        // Revoke old tokens if needed
        // $user->tokens()->delete();

        // Check user status
        if ($user->status !== 'مفعل') {
             Auth::logout(); // Log out if not active
             return response()->json(['message' => 'Account is not active.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load(['profile', 'skills', 'company']) // Load relevant relations (consider UserResource)
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        //$request->user()?->currentAccessToken()?->delete(); gemini solution

        return response()->json(['message' => 'Successfully logged out']);
    }

    // TODO: Implement forgotPassword logic if needed
}