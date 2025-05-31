<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobOpportunity;
use App\Models\User;
use App\Models\Company; // استيراد Company
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobOpportunityController extends Controller
{
    // ... (constructor, index - قد تحتاج لتعديل الفلترة لتستخدم CompanyID) ...

    public function index(Request $request)
    {
        // تحميل الشركة المرتبطة مباشرة بالوظيفة
        $query = JobOpportunity::query()->with(['user', 'company']); // <-- تعديل هنا

        // ... (منطق البحث) ...
         if ($request->filled('search')) {
            $searchTerm = $request->input('search');
             $query->where(function($q) use ($searchTerm) {
                $q->where('Job Title', 'like', "%{$searchTerm}%")
                  ->orWhere('Job Description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('company', fn($cq) => $cq->where('Name', 'like', "%{$searchTerm}%")); // <-- بحث باسم الشركة
             });
         }

        // الفلترة حسب الشركة
        if ($request->filled('company_id')) {
            // الآن الفلترة أسهل ومباشرة
            $query->where('CompanyID', $request->input('company_id')); // <-- تعديل هنا
        }
         if ($request->filled('type')) { $query->where('Type', $request->input('type')); }
         if ($request->filled('status')) { $query->where('Status', $request->input('status')); }


        $jobOpportunities = $query->latest('Date')->paginate(20)->withQueryString();
        $companies = Company::where('Status', 'Approved')->orderBy('Name')->pluck('Name', 'CompanyID'); // قائمة الشركات للفلترة

        return view('jobs.index', compact('jobOpportunities', 'companies')); // تمرير الشركات للفلترة
    }


    public function create()
    {
        // الأفضل الآن هو جلب الشركات المعتمدة ليختار الأدمن منها
        $companies = Company::where('Status', 'Approved')->orderBy('Name')->pluck('Name', 'CompanyID');
        // لا نزال بحاجة لقائمة المديرين لتعيين المنشئ (UserID)
        $managers = User::where('type', 'مدير شركة')
                         // يمكنك إضافة ->whereHas('company', fn($q) => $q->where('Status', 'Approved')) إذا أردت
                         ->orderBy('username')
                         ->pluck('username', 'UserID');

        return view('admin.job_opportunities.create', compact('companies', 'managers'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // المستخدم المنشئ
            'CompanyID' => 'required|exists:companies,CompanyID', // !!! إضافة التحقق من CompanyID !!!
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'required|string|max:255',
            'Skills' => 'nullable|string',
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:today',
            'Status' => 'required|string|in:مفعل,معلق,محذوف',
        ]);

        // إضافة التاريخ
        $dataToCreate = $validatedData + ['Date' => now()];

        JobOpportunity::create($dataToCreate);

        return redirect()->route('admin.job-opportunities.index')->with('success', 'Job Opportunity created successfully.');
    }


    public function show(JobOpportunity $jobOpportunity)
    {
        // الآن يمكن تحميل الشركة مباشرة
        $jobOpportunity->load(['user', 'company', 'jobApplications.user']);
        return view('admin.job_opportunities.show', compact('jobOpportunity'));
    }


    public function edit(JobOpportunity $jobOpportunity)
    {
         $jobOpportunity->load(['user', 'company']); // تحميل الشركة الحالية
         $companies = Company::where('Status', 'Approved')->orderBy('Name')->pluck('Name', 'CompanyID');
         $managers = User::where('type', 'مدير شركة')->orderBy('username')->pluck('username', 'UserID');
        return view('admin.job_opportunities.edit', compact('jobOpportunity', 'companies', 'managers'));
    }


    public function update(Request $request, JobOpportunity $jobOpportunity)
    {
         $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID',
            'CompanyID' => 'required|exists:companies,CompanyID', // !!! إضافة التحقق من CompanyID !!!
            'Job Title' => 'required|string|max:255',
            // ... باقي الحقول ...
            'Status' => 'required|string|in:مفعل,معلق,محذوف',
        ]);

        $jobOpportunity->update($validatedData);

        return redirect()->route('admin.job-opportunities.show', $jobOpportunity)->with('success', 'Job Opportunity updated successfully.');
    }

    // ... (destroy) ...
     public function destroy(JobOpportunity $jobOpportunity)
    {
        $jobOpportunity->delete();
        return redirect()->route('admin.job-opportunities.index')->with('success', 'Job Opportunity deleted successfully.');
    }
}