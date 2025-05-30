<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\JobOpportunity;
use App\Models\TrainingCourse; // تأكد أن هذا المودل موجود ومساره صحيح
use App\Models\Article;        // تأكد أن هذا المودل موجود ومساره صحيح
use App\Models\UpgradeRequest;  // !!! أضف هذا السطر لاستيراد المودل !!!
use Illuminate\Support\Facades\Auth; // Auth ليس مستخدمًا حاليًا في index، يمكن إزالته إذا لم تكن هناك حاجة له

class DashboardController extends Controller
{
     /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        // $this->middleware(['auth', 'isAdmin']); // يُفضل تفعيل هذا
    }

    /**
     * Show the admin dashboard with statistics.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'graduate_users' => User::where('type', 'خريج')->count(),
            'consultant_users' => User::where('type', 'خبير استشاري')->count(),
            'manager_users' => User::where('type', 'مدير شركة')->count(),
            'total_companies' => Company::count(),
            'pending_companies' => Company::where('Status', 'Pending')->count(), // تأكد أن عمود Status موجود في جدول companies
            'total_jobs' => JobOpportunity::count(),
            'active_jobs' => JobOpportunity::where('Status', 'مفعل')->count(), // تأكد من اسم الحالة
            'total_courses' => TrainingCourse::count(), // تأكد أن هذا المودل موجود ويعمل
            'total_articles' => Article::count(),     // تأكد أن هذا المودل موجود ويعمل
            'pending_upgrade_requests' => UpgradeRequest::where('status', 'pending')->count(), // الآن هذا سيعمل
            // لا حاجة لتكرار pending_companies و active_jobs، لقد تم تعريفها أعلاه
        ];


        // Fetch recent activities maybe?
        $recentUsers = User::latest()->take(5)->get(); // استخدام take(5) بدلاً من limit(5) أكثر شيوعًا مع Eloquent
        // يمكنك جلب أحدث طلبات الترقية هنا أيضًا إذا أردت عرضها في الداشبورد
        // $recentUpgradeRequests = UpgradeRequest::with('user')->where('status', 'pending')->latest()->take(5)->get();
        // $pendingCompanies = Company::where('Status', 'Pending')->latest()->take(5)->get();

        // قم بتمرير المتغيرات التي تريد عرضها فقط
        return view('admin.dashboard', compact('stats', 'recentUsers'/*, 'recentUpgradeRequests', 'pendingCompanies'*/));
    }
}