<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // تأكد من استيراد المودل
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; // استيراد Request للاستخدام في registered (اختياري)

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // أو المسار المناسب بعد التسجيل كخريج

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            // لا يوجد تحقق من 'type' هنا لأنه لم يعد يُرسل من النموذج
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'type' => 'خريج', // <-- !!! تعيين النوع افتراضيًا هنا !!!
            'status' => 'مفعل', // أو 'معلق' إذا كنت تريد موافقة أو تحقق
            // 'photo' => null, // إذا كان لديك هذا الحقل
            // 'email_verified_at' => now(), // إذا كنت تريد تفعيل البريد مباشرة
        ]);
    }

    /**
     * The user has been registered.
     * يمكنك تخصيص ما يحدث بعد التسجيل هنا.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    // protected function registered(Request $request, $user)
    // {
    //     // يمكنك هنا إضافة منطق إضافي، مثل إرسال بريد ترحيبي،
    //     // أو توجيه المستخدم إلى صفحة إكمال الملف الشخصي الخاصة بالخريج.
    //     // إذا لم تقم بإلغاء هذه الدالة، سيتم التوجيه إلى $this->redirectTo
    //
    //     // مثال للتوجيه لداشبورد الخريج مباشرة
    //     // return redirect()->route('graduate.dashboard');
    // }
}