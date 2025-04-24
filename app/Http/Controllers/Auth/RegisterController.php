<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Make sure to use your User model
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; // Import Request
use App\Models\Profile; // Import Profile if creating basic profile on registration

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Or redirect to profile creation, verification notice, etc.

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // Adjust validation rules based on your User model requirements
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'], // Use correct column name
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Use correct column name
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'], // Optional phone
            'type' => ['required', 'string', 'in:خريج,خبير استشاري,مدير شركة'], // Allowed types for registration
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'type' => $data['type'],
            'status' => 'مفعل', // Or 'معلق' pending verification/approval
            // 'email_verified' => false, // If using MustVerifyEmail
        ]);

        // Optionally create a basic profile automatically
        // Profile::create(['UserID' => $user->UserID]);

        // If type is 'مدير شركة', maybe trigger a company creation request/approval flow here

        return $user;
    }

     /**
      * The user has been registered.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  mixed  $user
      * @return mixed
      */
    // protected function registered(Request $request, $user)
    // {
    //     // Optional: Send welcome email, redirect based on type, etc.
    //     if ($user->type === 'مدير شركة') {
    //        // Maybe redirect to company info form or pending approval page
    //     }
    // }
}