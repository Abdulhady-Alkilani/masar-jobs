<?php

namespace App\Http\Controllers\Admin;

// ... (use statements) ...

class CompanyController extends Controller
{
    // ... (index, create, store, edit, update, destroy) ...

    /**
     * عرض تفاصيل الشركة مع وظائفها المرتبطة.
     */
    public function show(Company $company)
    {
         // الآن هذا يجب أن يعمل لأن العلاقة معرفة والوظائف مرتبطة بـ CompanyID
         $company->load(['user', 'jobOpportunities' => function ($query) {
             $query->latest('Date'); // تحميل المستخدم والوظائف المرتبطة مرتبة
         }]);

         return view('admin.companies.show', compact('company'));
    }
}