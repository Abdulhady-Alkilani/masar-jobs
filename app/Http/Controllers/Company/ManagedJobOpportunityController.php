<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobOpportunity;
use App\Models\Company; // غير مستخدم مباشرة هنا ولكن جيد للإشارة
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ManagedJobOpportunityController extends Controller
{
    /**
     * تطبيق Middleware.
     * يمكن وضعه هنا أو في ملف الـ routes.
     */
    public function __construct()
    {
        // middleware 'auth' يتم تطبيقه على المجموعة في routes/web.php
        // $this->middleware('isCompanyManager'); // مثال: إذا كان لديك middleware مخصص
    }

    /**
     * Middleware مخصص أو دالة مساعدة للتحقق من وجود شركة معتمدة للمدير.
     * @return \App\Models\Company|null
     */
    private function getManagerCompany()
    {
        $user = Auth::user();
        if (!$user || $user->type !== 'مدير شركة') {
            // هذا لا يجب أن يحدث إذا كان middleware الدور يعمل بشكل صحيح
            abort(403, 'Access denied. Not a company manager.');
        }

        $company = $user->company; // يفترض وجود علاقة 'company' في مودل User

        if (!$company) {
            // إذا لم يكن هناك شركة مرتبطة بالمدير
            return null;
        }
        return $company;
    }

    /**
     * عرض قائمة بفرص العمل الخاصة بشركة المدير الحالي فقط.
     */
    public function index(Request $request)
    {
        $company = $this->getManagerCompany();

        if (!$company) {
            return redirect()->route('company-manager.request.create') // توجيه لصفحة طلب إنشاء شركة
                             ->with('warning', 'You need to have a company profile to manage job opportunities. Please create or request one.');
        }

        if ($company->Status !== 'Approved') {
            return redirect()->route('company-manager.dashboard')
                             ->with('error', 'Your company profile is currently "' . $company->Status . '" and not active. Job management is disabled.');
        }

        $query = JobOpportunity::where('CompanyID', $company->CompanyID);

        if ($request->filled('search')) {
            $query->where('Job Title', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->filled('type')) {
            $query->where('Type', $request->input('type'));
        }
        if ($request->filled('status')) {
            $query->where('Status', $request->input('status'));
        }

        $jobOpportunities = $query->latest('Date')->paginate(15)->withQueryString();

        return view('company_manager.jobs.index', compact('jobOpportunities', 'company'));
    }

    /**
     * عرض نموذج إنشاء فرصة عمل جديدة.
     */
    public function create()
    {
        $company = $this->getManagerCompany();

        if (!$company) {
             return redirect()->route('company-manager.request.create')
                              ->with('warning', 'You must have a company profile to post jobs.');
        }
        if ($company->Status !== 'Approved') {
            return redirect()->route('company-manager.dashboard')
                             ->with('error', 'Your company profile must be active to post job opportunities.');
        }
        return view('company_manager.jobs.create', compact('company')); // تمرير الشركة للـ View (قد لا تحتاجها في النموذج)
    }

    /**
     * تخزين فرصة عمل جديدة.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $this->getManagerCompany(); // استخدام الدالة المساعدة

        if (!$company || $company->Status !== 'Approved') {
            // هذا التحقق مهم لمنع الإنشاء إذا تغيرت حالة الشركة فجأة
            return redirect()->route('company-manager.dashboard')
                             ->with('error', 'Cannot create job opportunity. Your company profile is not active.');
        }

        $validatedData = $request->validate([
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'required|string|max:255',
            'Skills' => 'nullable|string',
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:today',
            'Status' => 'required|string|in:مفعل,معلق',
        ]);

        JobOpportunity::create($validatedData + [
            'UserID' => $user->UserID,
            'CompanyID' => $company->CompanyID,
            'Date' => now(),
        ]);

        return redirect()->route('company-manager.job-opportunities.index')
                         ->with('success', 'Job opportunity created successfully.');
    }

    /**
     * عرض تفاصيل فرصة عمل.
     */
    public function show(JobOpportunity $jobOpportunity)
    {
        $company = $this->getManagerCompany();
        if (!$company || $jobOpportunity->CompanyID !== $company->CompanyID) {
            abort(403, 'Unauthorized access to this job opportunity.');
        }

        $jobOpportunity->load(['user', 'company', 'jobApplications.user']);
        return view('company_manager.jobs.show', compact('jobOpportunity'));
    }

    /**
     * عرض نموذج تعديل فرصة عمل.
     */
    public function edit(JobOpportunity $jobOpportunity)
    {
        $company = $this->getManagerCompany();
        if (!$company || $jobOpportunity->CompanyID !== $company->CompanyID) {
            abort(403, 'Unauthorized access to edit this job opportunity.');
        }
        return view('company_manager.jobs.edit', compact('jobOpportunity', 'company'));
    }

    /**
     * تحديث فرصة عمل.
     */
    public function update(Request $request, JobOpportunity $jobOpportunity)
    {
        $company = $this->getManagerCompany();
        if (!$company || $jobOpportunity->CompanyID !== $company->CompanyID) {
            abort(403, 'Unauthorized access to update this job opportunity.');
        }

        $validatedData = $request->validate([
            'Job Title' => 'required|string|max:255',
            'Job Description' => 'required|string',
            'Qualification' => 'nullable|string',
            'Site' => 'required|string|max:255',
            'Skills' => 'nullable|string',
            'Type' => 'required|string|in:وظيفة,تدريب',
            'End Date' => 'nullable|date|after_or_equal:today',
            'Status' => 'required|string|in:مفعل,معلق',
        ]);

        $jobOpportunity->update($validatedData);

        return redirect()->route('company-manager.job-opportunities.show', $jobOpportunity)
                         ->with('success', 'Job opportunity updated successfully.');
    }

    /**
     * حذف فرصة عمل.
     */
    public function destroy(JobOpportunity $jobOpportunity)
    {
        $company = $this->getManagerCompany();
        if (!$company || $jobOpportunity->CompanyID !== $company->CompanyID) {
            abort(403, 'Unauthorized access to delete this job opportunity.');
        }

        $jobOpportunity->jobApplications()->delete(); // حذف الطلبات المرتبطة
        $jobOpportunity->delete();

        return redirect()->route('company-manager.job-opportunities.index')
                         ->with('success', 'Job opportunity and its applications deleted successfully.');
    }
}