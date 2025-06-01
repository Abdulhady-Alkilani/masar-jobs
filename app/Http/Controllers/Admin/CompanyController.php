<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        // $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $query = Company::query()->with('user'); // Load manager

         // Add Filtering/Searching Logic
         if ($request->filled('search')) {
              $searchTerm = $request->input('search');
              $query->where(function ($q) use ($searchTerm) {
                  $q->where('Name', 'like', "%{$searchTerm}%")
                    ->orWhere('Email', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function($userQuery) use ($searchTerm) { // Search by manager username
                        $userQuery->where('username', 'like', "%{$searchTerm}%");
                    });
              });
         }
          if ($request->filled('status') && in_array($request->input('status'), ['Approved', 'Pending', 'Rejected'])) {
             $query->where('Status', $request->input('status'));
         }

         $companies = $query->latest()->paginate(20)->withQueryString();
         return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Find managers without companies to assign
        $managers = User::where('type', 'مدير شركة')->whereDoesntHave('company')->orderBy('username')->pluck('username', 'UserID');
        return view('admin.companies.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // !!! تأكد أن اسم العمود في جدول users هو UserID !!!
            'UserID' => 'required|exists:users,UserID|unique:companies,UserID',
            'Name' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255|unique:companies,Email',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255', // يفضل snake_case
            'Web site' => 'nullable|url|max:2048', // يفضل snake_case
            'Status' => ['required', 'string', Rule::in(['Approved', 'Pending', 'Rejected'])], // يجب أن يكون مطلوبًا
        ]);

        Company::create($validatedData);

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company) // Route Model Binding
    {
         // !!! الآن بعد إضافة العلاقة في المودل، هذا السطر سيعمل !!!
         // Eager load user (manager) and related job opportunities
         $company->load(['user', 'jobOpportunities' => function ($query) {
             $query->latest('Date'); // Order loaded jobs by date
         }]);

         // يمكنك أيضًا تحميل الدورات إذا كانت العلاقة موجودة
         // $company->load('trainingCourses');

         return view('admin.companies.show', compact('company')); // تمرير الشركة ببياناتها وعلاقاتها المحملة
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company) // Route Model Binding
    {
        // لا تحتاج لتحميل علاقات هنا عادةً
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company) // Route Model Binding
    {
        // !!! تأكد من اسم المفتاح الأساسي للشركة ('CompanyID' أو 'id') !!!
        $primaryKeyName = $company->getKeyName();

        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => ['nullable', 'email', 'max:255', Rule::unique('companies','Email')->ignore($company->{$primaryKeyName}, $primaryKeyName)],
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Web site' => 'nullable|url|max:2048',
            'Status' => ['required', 'string', Rule::in(['Approved', 'Pending', 'Rejected'])], // يجب أن يكون مطلوبًا
             // لا تسمح بتغيير UserID من هنا عادةً
        ]);

        $company->update($validatedData);

        return redirect()->route('admin.companies.show', $company)->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company) // Route Model Binding
    {
        // أضف منطق حذف البيانات المرتبطة هنا إذا لم تكن تستخدم cascade on delete
        // $company->jobOpportunities()->delete(); // مثال (كن حذرًا!)
        // $company->trainingCourses()->delete(); // مثال

        $company->delete();

        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully.');
    }
}