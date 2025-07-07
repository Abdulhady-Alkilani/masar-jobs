<?php

namespace App\Http\Controllers\API\V1;

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Models\Profile; // <-- تأكد من استيراد موديل Profile
use App\Models\Company;
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
            'phone' => 'nullable|string|max:20',
            'type' => 'required|string|in:خريج,خبير استشاري,مدير شركة', // <--- السماح باختيار النوع مجدداً
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'type' => $validatedData['type'], // <--- استخدام النوع المختار
            'status' => 'مفعل',
            // Add default email_verified status if needed
        ]);

        // إنشاء Profile افتراضي لجميع المستخدمين (أو فقط للخريج والاستشاري؟)
        // بما أن Profile يحتوي بيانات عامة، لننشئه للجميع
        if (!$user->profile) { // Avoid duplicates if logic changes later
             $user->profile()->create([]);
        }

        // لا يتم إنشاء سجل الشركة هنا للمدير، سيتم بطلبه لاحقاً


        $token = $user->createToken('auth_token')->plainTextToken;

        // Load relations relevant immediately after registration
        $user->load(['profile', 'company']); // company relation might be null for new manager/graduate

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user // Return user data with profile
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