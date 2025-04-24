<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Requires user to be logged in to access this controller
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * Redirects user based on their type.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect based on user type
        switch ($user->type) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'مدير شركة':
                return redirect()->route('company-manager.dashboard');
            case 'خبير استشاري':
                return redirect()->route('consultant.dashboard');
            case 'خريج':
                return redirect()->route('graduate.dashboard');
            default:
                // Fallback for unknown types or basic authenticated user view
                 return view('home'); // A generic home view
        }
    }
}