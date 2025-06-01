<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // <--- أضف هذا السطر
use Illuminate\Support\Facades\Auth; // <--- أضف هذا السطر

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * (سيتم تجاوز هذا بواسطة دالة authenticated)
     * @var string
     */
    // protected $redirectTo = '/home'; // يمكنك إزالة هذا أو تركه كاحتياط

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // middleware auth أصبح الآن يُطبق ضمنيًا في trait logout
        // $this->middleware('auth')->only('logout'); // يمكنك إزالته إذا أردت
    }

    /**
     * The user has been authenticated.
     * يتم استدعاؤها بعد نجاح تسجيل الدخول.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user // المستخدم الذي قام بتسجيل الدخول
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // التحقق من نوع المستخدم (افترض أن لديك عمود 'type' في جدول users)
        switch ($user->type) {
            case 'Admin':
                return redirect()->route('admin.dashboard'); // توجيه الأدمن
                break;
            case 'مدير شركة':
                // تحقق إذا كانت شركته معتمدة أو لا تزال معلقة (إذا كان لديك حقل Status)
                // if ($user->company && $user->company->Status === 'Approved') {
                //     return redirect()->route('company-manager.dashboard');
                // } else {
                //     // توجيهه لصفحة انتظار الموافقة أو لوحة تحكم محدودة
                //     return redirect('/company/pending-approval'); // مثال
                // }
                return redirect()->route('company-manager.dashboard'); // توجيه مدير الشركة
                break;
            case 'خبير استشاري':
                return redirect()->route('consultant.dashboard'); // توجيه الاستشاري
                break;
            case 'خريج':
                return redirect()->route('graduate.dashboard'); // توجيه الخريج
                break;
            default:
                // إذا لم يكن أي من الأنواع المحددة، وجهه إلى الصفحة الرئيسية الافتراضية
                return redirect('/home');
                break;
        }
    }
}