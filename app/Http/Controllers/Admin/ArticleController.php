<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User; // To filter by author if needed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // For deleting photos
use Illuminate\Support\Facades\Auth; // For check (redundant with middleware)

class ArticleController extends Controller
{
    /**
     * Apply middleware for authentication and admin role.
     */
    public function __construct()
    {
        $this->middleware(['auth'/*, 'isAdmin'*/]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::query()->with('user'); // Load author

        if ($request->filled('search')) {
            // Search logic
        }
        if ($request->filled('type')) {
             $query->where('Type', $request->input('type'));
        }

        $articles = $query->latest('Date')->paginate(20);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Admin can create articles, potentially assign author?
        $authors = User::whereIn('type', ['خبير استشاري', 'Admin'])->orderBy('username')->pluck('username', 'UserID');
        return view('admin.articles.create', compact('authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Reuse validation/logic from public ArticleController store method
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // Admin selects author
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Type' => 'required|string|in:استشاري,نصائح',
            'Article Photo' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('Article Photo') && $request->file('Article Photo')->isValid()) {
            $imagePath = $request->file('Article Photo')->store('article_photos', 'public');
        }

        Article::create([
            'UserID' => $validatedData['UserID'],
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Type' => $validatedData['Type'],
            'Article Photo' => $imagePath,
            'Date' => now(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load('user');
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
         $authors = User::whereIn('type', ['خبير استشاري', 'Admin'])->orderBy('username')->pluck('username', 'UserID');
         $article->load('user');
        return view('admin.articles.edit', compact('article', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        // Reuse validation/logic from public ArticleController update method
        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // Admin might change author
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Type' => 'required|string|in:استشاري,نصائح',
            'Article Photo' => 'nullable|image|max:2048', // Handle update/delete logic
        ]);

        // Handle file update/delete...

        $article->update($validatedData);

        return redirect()->route('admin.articles.show', $article)->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Reuse logic from public ArticleController destroy method
        if ($article->{'Article Photo'}) {
            Storage::disk('public')->delete($article->{'Article Photo'});
        }
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }
}