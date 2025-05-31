<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company; // Assuming requests are stored as companies with a status
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyRequestApprovalController extends Controller
{
    /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        // $this->middleware(['auth', 'isAdmin']); // تفعيل Middleware
    }

    /**
     * Display a listing of pending company requests.
     */
    public function index()
    {
        // ... (الكود الخاص بـ index) ...
        $pendingCompanies = Company::where('Status', 'Pending') // تأكد من اسم الحالة
                                   ->with('user')
                                   ->latest()
                                   ->paginate(20);
        return view('admin.company_requests.index', compact('pendingCompanies'));
    }

    /**
     * Display the specified pending company request.
     */
    public function show(Company $company)
    {
        // أزل أو علق الـ dd من هنا
    
        if ($company->Status !== 'Pending') { // تأكد من اسم الحالة الصحيح
            // قد ترغب في إعادة التوجيه إلى صفحة عرض الشركة العامة بدلاً من الطلبات
            return redirect()->route('admin.companies.show', $company)->with('warning', 'This company request has already been processed.');
        }
        $company->load('user'); // تحميل بيانات المستخدم المرتبط
        return view('admin.company_requests.show', compact('company')); // تمرير الكائن الممتلئ بالبيانات
    }

    /**
     * Update the specified resource in storage (Approve or Reject).
     * Using UPDATE (PUT/PATCH) method for approval/rejection.
     */
    public function update(Request $request, Company $company)
    {
        // TODO: Form Request Validation
        $validatedData = $request->validate([
            'action' => 'required|string|in:approve,reject',
            'rejection_reason' => 'nullable|string|max:1000|required_if:action,reject',
        ]);

        if ($company->Status !== 'Pending') {
            return back()->with('error', 'This company request has already been processed.');
        }

        if ($validatedData['action'] === 'approve') {
            $company->update(['Status' => 'Approved']); // Or 'Active'
            // TODO: Notify manager of approval
            return redirect()->route('admin.company-requests.index')
                             ->with('success', "Company '{$company->Name}' approved successfully.");
        } elseif ($validatedData['action'] === 'reject') {
            $company->update([
                'Status' => 'Rejected',
                // Optionally store rejection reason if you add a column
                // 'rejection_reason' => $validatedData['rejection_reason']
                ]);
            // TODO: Notify manager of rejection and reason
             return redirect()->route('admin.company-requests.index')
                             ->with('success', "Company '{$company->Name}' rejected successfully.");
        }

        return back()->with('error', 'Invalid action specified.');
    }


    // Admin doesn't typically create/edit requests this way, they approve/reject existing ones.
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(Company $company) { abort(404); }

     /**
      * Optionally allow deleting a PENDING request entirely.
      */
    public function destroy(Company $company)
    {
         if ($company->Status !== 'Pending') {
            return back()->with('error', 'Cannot delete an already processed company request.');
         }
         // TODO: Notify manager?
         $company->delete();
         return redirect()->route('admin.company-requests.index')->with('success', 'Pending company request deleted.');
    }
}