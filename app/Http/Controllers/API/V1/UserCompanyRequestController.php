<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\User; // نحتاجه للتأكد من نوع المستخدم

class UserCompanyRequestController extends Controller
{
    /**
     * Store a new company creation request.
     * Route: POST /api/v1/company-requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user(); // المستخدم المصادق عليه

        // TODO: تحقق: هل المستخدم الحالي لديه الصلاحية لتقديم طلب؟
        // مثلاً: ليس أدمن، ليس مدير شركة حالي، ليس لديه طلب معلق مسبقاً.
        // يمكنك إضافة هذه التحققات هنا أو استخدام Gates/Policies
        // مثال بسيط للتحقق داخل المتحكم:
        if ($user->type === 'Admin' || $user->type === 'مدير شركة') {
             return response()->json(['message' => 'You are already a manager or admin.'], 403);
        }
        // TODO: تحقق من عدم وجود طلب معلق سابقاً لنفس المستخدم

        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'Description' => 'nullable|string',
            'Country' => 'nullable|string|max:100',
            'City' => 'nullable|string|max:100',
            'Detailed Address' => 'nullable|string|max:255',
            'Media' => 'nullable|string', // أو معالجة رفع الملف هنا
            'Web site' => 'nullable|url|max:255',
        ]);

        // إنشاء سجل الشركة مع حالة 'pending' وربطه بالمستخدم الحالي
        $company = Company::create([
            'UserID' => $user->UserID, // ربط الشركة بالمستخدم الحالي
            'Name' => $validatedData['Name'],
            'Email' => $validatedData['Email'] ?? null,
            'Phone' => $validatedData['Phone'] ?? null,
            'Description' => $validatedData['Description'] ?? null,
            'Country' => $validatedData['Country'] ?? null,
            'City' => $validatedData['City'] ?? null,
            'Detailed Address' => $validatedData['Detailed Address'] ?? null,
            'Media' => $validatedData['Media'] ?? null,
            'Web site' => $validatedData['Web site'] ?? null,
            'status' => 'pending', // تعيين الحالة كـ 'معلق' بانتظار موافقة الأدمن
        ]);

        // TODO: إرسال إشعار للأدمن بوجود طلب جديد

        return response()->json([
            'message' => 'Company creation request submitted successfully. Waiting for admin approval.',
            'company' => $company // قد تعيد بيانات الشركة أو جزء منها
        ], 201); // 201 Created for successful creation
    }
}