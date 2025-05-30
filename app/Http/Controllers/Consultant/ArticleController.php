<?php

namespace App\Http\Controllers\Consultant; // <-- Namespace صحيح

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * تطبيق middleware المصادقة ودور الاستشاري
     */
    public function __construct()
    {
         // تأكد من تطبيق Middleware الصحيح هنا أو في الـ Routes
         // $this->middleware(['auth']); // 'isConsultant' يمكن تطبيقه في مجموعة الـ Route
    }

    /**
     * عرض قائمة بمقالات الاستشاري الحالي فقط.
     */
    public function index()
    {
        // جلب مقالات المستخدم الحالي فقط
        $articles = Article::where('UserID', Auth::id())
                           ->latest('Date')
                           ->paginate(15); // أو العدد المناسب

        // استخدام View خاص بالاستشاري
        return view('consultant.articles.index', compact('articles'));
    }

    /**
     * عرض نموذج إنشاء مقال جديد للاستشاري.
     */
    public function create()
    {
        // استخدام View خاص بالاستشاري
        return view('consultant.articles.create');
    }

    /**
     * تخزين مقال جديد للاستشاري.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Type' => ['required', 'string', Rule::in(['استشاري', 'نصائح'])],
            'Article Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('Article Photo') && $request->file('Article Photo')->isValid()) {
            $imagePath = $request->file('Article Photo')->store('article_photos', 'public');
        }

        Article::create([
            'UserID' => Auth::id(), // تعيين المستخدم الحالي تلقائيًا
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Type' => $validatedData['Type'],
            'Article Photo' => $imagePath,
            'Date' => now(),
        ]);

        return redirect()->route('consultant.articles.index')->with('success', 'Article created successfully.');
    }

    /**
     * عرض تفاصيل مقال (يمكن استخدام view خاص أو توجيه للعرض العام).
     */
    public function show(Article $article)
    {
        // التحقق من الملكية (الاستشاري يرى مقاله فقط)
        if ($article->UserID !== Auth::id()) {
             abort(403);
        }

        // يمكنك استخدام نفس View الأدمن إذا كان مناسبًا أو إنشاء واحد خاص
        $article->load('user'); // تحميل المؤلف (هو نفسه المستخدم الحالي)
        return view('consultant.articles.show', compact('article')); // View خاص بالاستشاري
        // أو يمكنك توجيه للعرض العام:
        // return redirect()->route('articles.show', $article);
    }

    /**
     * عرض نموذج تعديل مقال الاستشاري.
     */
    public function edit(Article $article)
    {
        // التحقق من الملكية
        if ($article->UserID !== Auth::id()) {
             abort(403);
        }
        // $this->authorize('update', $article); // استخدام Policy أفضل

        // استخدام View خاص بالاستشاري
        return view('consultant.articles.edit', compact('article'));
    }

    /**
     * تحديث مقال الاستشاري.
     */
    public function update(Request $request, Article $article)
    {
        // التحقق من الملكية
         if ($article->UserID !== Auth::id()) {
             abort(403);
        }
        // $this->authorize('update', $article);

        $validatedData = $request->validate([
             'Title' => 'required|string|max:255',
             'Description' => 'required|string',
             'Type' => ['required', 'string', Rule::in(['استشاري', 'نصائح'])],
             'Article Photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             // 'delete_photo' => 'nullable|boolean'
        ]);

        // --- منطق تحديث/حذف الصورة ---
        $imagePath = $article->{'Article Photo'};
        if ($request->hasFile('Article Photo') && $request->file('Article Photo')->isValid()) {
            if ($imagePath) { Storage::disk('public')->delete($imagePath); }
            $imagePath = $request->file('Article Photo')->store('article_photos', 'public');
        }
        // elseif ($request->boolean('delete_photo') && $imagePath) { ... }

        $article->update([
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Type' => $validatedData['Type'],
            'Article Photo' => $imagePath,
            // لا نحدث UserID
        ]);

        // إعادة التوجيه إلى قائمة مقالات الاستشاري
        return redirect()->route('consultant.articles.index')->with('success', 'Article updated successfully.');
    }

    /**
     * حذف مقال الاستشاري.
     */
    public function destroy(Article $article)
    {
         // التحقق من الملكية
         if ($article->UserID !== Auth::id()) {
             abort(403);
        }
         // $this->authorize('delete', $article);

        if ($article->{'Article Photo'}) {
            Storage::disk('public')->delete($article->{'Article Photo'});
        }

        $article->delete();
        return redirect()->route('consultant.articles.index')->with('success', 'Article deleted successfully.');
    }
}