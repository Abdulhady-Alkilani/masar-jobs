<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // إذا كنت ستتحقق من صلاحية الأدمن يدوياً هنا
use Illuminate\Support\Facades\DB; // لاستخدام المعاملات (Transactions)
use Illuminate\Support\Facades\Log; // لتسجيل الأخطاء

use App\Models\Company; // تأكد من المسار الصحيح لموديل الشركة
use App\Models\User; // تأكد من المسار الصحيح لموديل المستخدم
use App\Http\Resources\CompanyResource; // إذا كنت تستخدم API Resources (موصى به)
use App\Http\Resources\CompanyCollection; // إذا كنت تستخدم API Resources (موصى به)


class ApiAdminCompanyRequestController extends Controller
{
    /**
     * Instantiate a new controller instance.
     * Apply admin check middleware to all methods in this controller.
     */
    // public function __construct()
    // {
    //     // TODO: Apply middleware to ensure only admins can access these routes
    //     // $this->middleware('isAdmin'); // Assuming you create an 'isAdmin' middleware
    //     // Or use Laravel's Gate/Policy checks within methods
    // }


    /**
     * Display a listing of pending company creation requests.
     * Route: GET /api/v1/admin/company-requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\CompanyCollection
     */
    public function index(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        // Example using Gate: $this->authorize('viewAny', Company::class); // Requires a Policy for Company

        // جلب الشركات التي حالتها 'pending' مع بيانات المستخدم المرتبط بها
        // Eager load the user relationship
        $pendingCompanies = Company::where('status', 'pending')
                                   ->with('user:UserID,first_name,last_name,email,type') // تأكد من تحميل البيانات اللازمة للمستخدم
                                   ->latest() // ترتيب الأحدث أولاً
                                   ->paginate(15); // تطبيق Pagination


        // استخدام CompanyCollection إذا كنت تستخدم API Resources
        // return new CompanyCollection($pendingCompanies);

        // أو إعادة البيانات كـ JSON مباشرة
        return response()->json($pendingCompanies); // يعيد استجابة Paginated JSON
    }

    /**
     * Approve a company creation request.
     * Route: PUT /api/v1/admin/company-requests/{company}/approve
     *
     * @param  \App\Models\Company  $company // Route model binding
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\CompanyResource
     */
    public function approve(Company $company)
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        // Example using Gate: $this->authorize('approve', $company); // Requires a Policy for Company and 'approve' Gate

        // تحقق مما إذا كانت الشركة بالفعل في حالة 'pending'
        if ($company->status !== 'pending') {
            return response()->json(['message' => 'Company is not pending approval.'], 400); // Bad Request
        }

        // TODO: تحقق من وجود المستخدم المرتبط بالشركة
        // يفترض أن العلاقة 'user' محددة في موديل Company (belongsTo)
        $user = $company->user; // جلب المستخدم المرتبط

        // إذا لم يتم العثور على المستخدم المرتبط (حالة غير متوقعة لكن ممكنة)
        if (!$user) {
             Log::error("Admin attempted to approve company ID {$company->CompanyID} but no associated user found.");
             return response()->json(['message' => 'Associated user not found.'], 404); // Not Found
        }

        // استخدام المعاملات (Transactions) لضمان تحديث الشركة والمستخدم معاً أو لا شيء
        DB::beginTransaction();

        try {
            // 1. تحديث حالة الشركة إلى 'approved'
            $company->status = 'approved';
            $company->save();

            // 2. تحديث دور المستخدم المرتبط ليصبح 'مدير شركة'
            // تحقق قبل التحديث إذا لم يكن بالفعل مدير شركة أو أدمن (حالة غير متوقعة لكن للأمان)
            if ($user->type !== 'مدير شركة' && $user->type !== 'Admin') {
                 $user->type = 'مدير شركة';
                 $user->save();
            } else {
                 // إذا كان المستخدم بالفعل مدير شركة أو أدمن، قد تريد تسجيل هذا كتحذير
                 Log::warning("Admin attempted to approve company for user ID {$user->UserID} who is already type {$user->type}");
            }

            // TODO: إرسال إشعار للمستخدم بأن شركته تمت الموافقة عليها (مثلاً Notification)

            DB::commit(); // تطبيق التغييرات

            // إعادة تحميل المستخدم المرتبط لضمان أن العلاقة في كائن الشركة تحتوي على أحدث بيانات المستخدم
             $company->load('user');

            // استخدام CompanyResource إذا كنت تستخدم API Resources
            // return new CompanyResource($company);

            // أو إعادة البيانات كـ JSON مباشرة
            return response()->json([
                'message' => 'Company approved successfully.',
                'company' => $company // إعادة بيانات الشركة المحدثة مع بيانات المستخدم المرتبط
            ]); // 200 OK هو الافتراضي

        } catch (\Exception $e) {
            DB::rollBack(); // التراجع عن التغييرات في حالة حدوث خطأ
            Log::error("Error approving company ID {$company->CompanyID}: {$e->getMessage()}"); // تسجيل الخطأ في سجلات Laravel

            return response()->json(['message' => 'Failed to approve company due to a server error.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }

    /**
     * Reject a company creation request.
     * Route: PUT /api/v1/admin/company-requests/{company}/reject
     *
     * @param  \App\Models\Company  $company // Route model binding
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\CompanyResource
     */
    public function reject(Company $company)
    {
        // TODO: Add Authorization check (Gate/Policy or Middleware) - Admin Only
        // Example using Gate: $this->authorize('reject', $company); // Requires a Policy for Company and 'reject' Gate

        // تحقق مما إذا كانت الشركة بالفعل في حالة 'pending'
        if ($company->status !== 'pending') {
            return response()->json(['message' => 'Company is not pending approval.'], 400); // Bad Request
        }

        // استخدام المعاملات لضمان التناسق إذا كان هناك عمليات أخرى مرتبطة بالرفض
        DB::beginTransaction();

        try {
            // 1. تحديث حالة الشركة إلى 'rejected'
            $company->status = 'rejected';
            $company->save();

            // TODO: إرسال إشعار للمستخدم بأن طلبه تم رفضه (مثلاً Notification)

            DB::commit(); // تطبيق التغييرات

             // إعادة تحميل المستخدم المرتبط إذا كنت تريد إعادته في الاستجابة
             $company->load('user');

            // استخدام CompanyResource إذا كنت تستخدم API Resources
            // return new CompanyResource($company);

            // أو إعادة البيانات كـ JSON مباشرة
            return response()->json([
                'message' => 'Company rejected successfully.',
                'company' => $company // إعادة بيانات الشركة المحدثة
            ]); // 200 OK هو الافتراضي

        } catch (\Exception $e) {
            DB::rollBack(); // التراجع عن التغييرات
             Log::error("Error rejecting company ID {$company->CompanyID}: {$e->getMessage()}"); // تسجيل الخطأ

            return response()->json(['message' => 'Failed to reject company due to a server error.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }

        // الخيار البديل للرفض (حذف سجل الشركة بدلاً من تغيير حالته):
        /*
        public function reject(Company $company) {
             // TODO: Authorization & pending check...
             try {
                 $company->delete(); // حذف الشركة
                 // TODO: إرسال إشعار بأن الطلب تم رفضه وتم حذف البيانات
                 return response()->json(['message' => 'Company rejected and removed successfully.']); // 200 OK
             } catch (\Exception $e) {
                  Log::error("Error deleting rejected company ID {$company->CompanyID}: {$e->getMessage()}");
                  return response()->json(['message' => 'Failed to reject company due to a server error.', 'error' => $e->getMessage()], 500);
             }
        }
        */
    }
}