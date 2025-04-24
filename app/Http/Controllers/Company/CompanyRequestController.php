<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company; // Needed to check if company exists

class CompanyRequestController extends Controller
{
    /**
     * Apply middleware for authentication and ensure user is a potential manager.
     */
    public function __construct()
    {
        // Allow logged-in users who are 'مدير شركة' but don't have a company yet
        $this->middleware(['auth'])->only(['create', 'store']);
    }

    /**
     * Show the form for requesting company creation.
     */
    public function create()
    {
        $user = Auth::user();

        // Ensure user is designated as a manager type and doesn't already have a company
        if ($user->type !== 'مدير شركة') {
             return redirect('/home')->with('error', 'Only users designated as Company Managers can request company creation.');
        }
        if (Company::where('UserID', $user->UserID)->exists()) {
             return redirect()->route('company-manager.dashboard')->with('info', 'Your company profile already exists.');
        }

        return view('company_manager.request.create');
    }

    /**
     * Store a newly created company request (or create company directly pending approval).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Double-check permissions and existence
        if ($user->type !== 'مدير شركة' || Company::where('UserID', $user->UserID)->exists()) {
             abort(403);
        }

        // TODO: Use Form Request Validation
         $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255|unique:companies,Email',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Web site' => 'nullable|url|max:2048',
            // Include fields for business registration proof if needed
            // 'registration_document' => 'nullable|file|max:5120',
        ]);

        // Option 1: Create company directly with 'Pending' status for Admin approval
        $company = Company::create([
             'UserID' => $user->UserID,
             'Name' => $validatedData['Name'],
             'Email' => $validatedData['Email'],
             'Phone' => $validatedData['Phone'],
             'Description' => $validatedData['Description'],
             'Country' => $validatedData['Country'],
             'City' => $validatedData['City'],
             'Detailed Address' => $validatedData['Detailed Address'],
             'Web site' => $validatedData['Web site'],
             // 'Status' => 'Pending', // Add a Status column to Company model if needed
        ]);

        // TODO: Handle document upload if applicable
        // if ($request->hasFile('registration_document')) { ... }

        // TODO: Notify Admin about the new company pending approval

        // Option 2: Store request in a separate 'company_requests' table

        // Redirect manager to a "pending approval" page or their (limited) dashboard
        return redirect()->route('home')->with('success', 'Company profile submitted successfully. It will be reviewed by an administrator.');
    }
}