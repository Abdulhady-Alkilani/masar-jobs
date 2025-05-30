<?php

namespace App\Http\Controllers\Company; // <-- Namespace صحيح

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyProfileController extends Controller
{
    // تطبيق Middleware للتحقق من دور مدير الشركة
    public function __construct()
    {
        // $this->middleware(['auth', 'isCompanyManager']); // طبقه في الـ Routes أو هنا
    }

    /**
     * عرض ملف الشركة الخاص بالمدير الحالي.
     */
    public function show()
    {
        $user = Auth::user();
        $company = $user->company()->with('user')->first(); // جلب الشركة مع المستخدم (المدير)

        if (!$company) {
            // إذا لم يكن لديه شركة، يمكن توجيهه لطلب إنشاء شركة
            return redirect()->route('company-manager.request.create')->with('info', 'Please create or request your company profile.');
        }

        return view('company_manager.profile.show', compact('company'));
    }

    /**
     * عرض نموذج تعديل ملف الشركة.
     */
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company-manager.request.create')->with('error', 'Company profile not found. Please create one first.');
        }

        // تأكد أن المدير الحالي هو مالك هذه الشركة (حماية إضافية)
        if ($company->UserID !== $user->UserID) {
            abort(403, 'Unauthorized action.');
        }

        return view('company_manager.profile.edit', compact('company'));
    }

    /**
     * تحديث ملف الشركة.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $company->UserID !== $user->UserID) {
            abort(403, 'Unauthorized action or company not found.');
        }

        // تأكد من اسم المفتاح الأساسي للشركة ('CompanyID' أو 'id')
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
            // لا نسمح بتغيير UserID أو Status من هنا عادةً
        ]);

        $company->update($validatedData);

        return redirect()->route('company-manager.profile.show')->with('success', 'Company profile updated successfully.');
    }
}