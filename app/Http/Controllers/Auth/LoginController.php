<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Import Request

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Adjust as needed, e.g., based on role

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     * Use 'username' or 'email' for login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $field = filter_var($request->input($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username() // 'email' by default
            : 'username'; // Allow login with username

        return [
            $field => $request->input($this->username()),
            'password' => $request->input('password'),
        ];
    }

     /**
      * The user has been authenticated.
      * Optionally redirect based on role here.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  mixed  $user
      * @return mixed
      */
    // protected function authenticated(Request $request, $user)
    // {
    //     if ($user->type === 'Admin') {
    //         return redirect()->route('admin.dashboard');
    //     } elseif ($user->type === 'مدير شركة') {
    //          return redirect()->route('company-manager.dashboard');
    //     } // etc...
    //
    //     return redirect($this->redirectTo);
    // }
}