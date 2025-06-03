<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use App\Models\Profile; // تأكد أن هذا المودل موجود ومعرف بشكل صحيح
use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // !!! استيراد مهم لمعالجة الملفات !!!
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * تطبيق Middleware للمصادقة ودور الخريج.
     */
    public function __construct()
    {
        // يُفضل تفعيل هذا في مجموعة الـ Route أو هنا مباشرة
        // $this->middleware(['auth', 'isGraduate']);
        $this->middleware('auth'); // على الأقل تأكد من المصادقة
    }

    /**
     * عرض ملف الخريج.
     */
   public function show()
{
    $user = Auth::user()->load(['profile', 'skills']);
    if (!$user->profile) {
         Profile::create(['UserID' => $user->UserID]);
         $user->refresh()->load('profile');
    }
    return view('graduate.profile.show', compact('user'));
}

    /**
     * عرض نموذج تعديل ملف الخريج.
     */
    public function edit()
    {
        $user = Auth::user()->load(['profile', 'skills']);
        if (!$user->profile) {
            Profile::create(['UserID' => $user->UserID]);
            $user->refresh()->load('profile');
        }

        $skills = Skill::orderBy('Name')->get(); // جميع المهارات المتاحة

        // جلب مهارات المستخدم الحالية مع مستوياتها
        $userSkillsWithLevels = [];
        if ($user->skills->isNotEmpty()) {
            foreach ($user->skills as $userSkill) {
                $userSkillsWithLevels[$userSkill->SkillID] = $userSkill->pivot->Stage;
            }
        }

        return view('graduate.profile.edit', compact('user', 'skills', 'userSkillsWithLevels'));
    }

    /**
     * تحديث ملف الخريج في قاعدة البيانات.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        // تأكد أن البروفايل موجود أو أنشئه إذا لم يكن (لضمان عدم حدوث خطأ)
        $profile = $user->profile ?? Profile::create(['UserID' => $user->UserID]);


        // --- التحقق من صحة البيانات ---
        // (يفضل بشدة استخدام Form Request Classes مخصصة هنا)
        $validatedUserData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+\(\)]*$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // مثل 2MB
            'delete_current_photo' => 'nullable|boolean', // لإضافة خيار حذف الصورة
        ]);

        $validatedProfileData = $request->validate([
            'University' => 'nullable|string|max:255',
            'GPA' => 'nullable|numeric|min:0|max:5', // أو حسب نظام المعدل
            'Personal Description' => 'nullable|string|max:2000',
            'Technical Description' => 'nullable|string|max:2000',
            'Git Hyper Link' => 'nullable|url:http,https|max:2048',
        ]);

        $validatedSkillsData = $request->validate([
            'skills' => 'nullable|array',
            'skills.*' => ['nullable', 'string', Rule::in(['مبتدئ', 'متوسط', 'متقدم'])],
        ]);


        // --- معالجة الصورة الشخصية ---
        $photoPathForDB = $user->photo; // ابدأ بالمسار الحالي

        // 1. إذا طلب المستخدم حذف الصورة الحالية
        if ($request->boolean('delete_current_photo') && $user->photo) {
            if (Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPathForDB = null; // قم بتعيينها إلى null في قاعدة البيانات
        }

        // 2. إذا تم رفع صورة جديدة (وهذا سيتجاوز خيار الحذف إذا تم اختيار كلاهما)
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // حذف الصورة القديمة إذا كانت موجودة (حتى لو لم يطلب الحذف بشكل صريح، لأننا نرفع جديدة)
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            // تخزين الصورة الجديدة في storage/app/public/user_photos
            // والقيمة المخزنة في قاعدة البيانات ستكون 'user_photos/filename.jpg'
            $newPath = $request->file('photo')->store('user_photos', 'public');
            $photoPathForDB = $newPath;
        }

        // إضافة مسار الصورة (الجديد أو القديم أو null) إلى بيانات المستخدم للتحديث
        $userDataToUpdate = $validatedUserData;
        // نقوم بتحديث حقل الصورة فقط إذا تغير (تم رفع جديد أو طلب حذف)
        // أو إذا كانت $validatedUserData تحتوي بالفعل على 'photo' (من رفع ملف)
        if ($photoPathForDB !== $user->photo || array_key_exists('photo', $userDataToUpdate) ) {
             $userDataToUpdate['photo'] = $photoPathForDB;
        } else {
            // إذا لم يتم رفع صورة جديدة ولم يتم طلب الحذف، أزل 'photo' من المصفوفة لتجنب تحديثها بـ null إذا لم يكن موجودًا في الطلب
            unset($userDataToUpdate['photo']);
        }
        unset($userDataToUpdate['delete_current_photo']); // لا نخزن هذا في قاعدة البيانات


        // --- تحديث بيانات المستخدم ---
        $user->update($userDataToUpdate);

        // --- تحديث أو إنشاء بيانات البروفايل ---
        if ($profile) {
            $profile->update($validatedProfileData);
        }
        // لا حاجة لـ else لإنشاء البروفايل لأننا تأكدنا من وجوده في بداية الدالة

        // --- مزامنة المهارات ---
        $skillsToSync = [];
        if (!empty($validatedSkillsData['skills'])) {
            foreach ($validatedSkillsData['skills'] as $skillId => $level) {
                if (!empty($level) && Skill::where('SkillID', $skillId)->exists()) {
                    $skillsToSync[$skillId] = ['Stage' => $level];
                }
            }
        }
        $user->skills()->sync($skillsToSync);


        return redirect()->route('graduate.profile.show')->with('success', 'Profile updated successfully!');
    }
}