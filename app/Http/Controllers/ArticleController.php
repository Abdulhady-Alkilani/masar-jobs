<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // To get logged-in user

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Public view - show all published/active articles
        // Add filtering/searching capabilities later
        $articles = Article::latest()->paginate(15); // Example pagination
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     * Accessible by Consultant or Admin
     */
    public function create()
    {
        // TODO: Add Authorization (Policy or Gate for 'create article')
        if (!in_array(Auth::user()->type, ['خبير استشاري', 'Admin'])) {
             abort(403, 'Unauthorized action.');
        }
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     * Accessible by Consultant or Admin
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization
         if (!in_array(Auth::user()->type, ['خبير استشاري', 'Admin'])) {
             abort(403, 'Unauthorized action.');
        }

        // TODO: Use Form Request Validation (e.g., StoreArticleRequest)
        $validatedData = $request->validate([
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Type' => 'required|string|in:استشاري,نصائح', // Match defined types
            'Article Photo' => 'nullable|image|max:2048', // Example validation for image upload
            // Add other fields as needed
        ]);

        // Handle file upload if present
        $imagePath = null;
        if ($request->hasFile('Article Photo') && $request->file('Article Photo')->isValid()) {
             // Consider using public disk or s3. Store path in DB.
            $imagePath = $request->file('Article Photo')->store('article_photos', 'public');
        }

        $article = Article::create([
            'UserID' => Auth::id(), // Assign the logged-in user
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Type' => $validatedData['Type'],
            'Article Photo' => $imagePath,
            'Date' => now(), // Set publication date
        ]);

        return redirect()->route('articles.show', $article)->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article) // Route Model Binding
    {
        // Public view
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     * Accessible by author (Consultant) or Admin
     */
    public function edit(Article $article)
    {
        // TODO: Add Authorization (Policy: user can update article?)
         if (Auth::id() !== $article->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
         }

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     * Accessible by author (Consultant) or Admin
     */
    public function update(Request $request, Article $article)
    {
        // TODO: Add Authorization (Policy: user can update article?)
         if (Auth::id() !== $article->UserID && Auth::user()->type !== 'Admin') {
             abort(403, 'Unauthorized action.');
         }

        // TODO: Use Form Request Validation (e.g., UpdateArticleRequest)
        $validatedData = $request->validate([
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Type' => 'required|string|in:استشاري,نصائح',
            'Article Photo' => 'nullable|image|max:2048',
        ]);

         // Handle file upload update (delete old if new one is uploaded)
         // ... (logic for updating/deleting photo) ...

        $article->update($validatedData);

        return redirect()->route('articles.show', $article)->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * Accessible by author (Consultant) or Admin
     */
    public function destroy(Article $article)
    {
       // TODO: Add Authorization (Policy: user can delete article?)
        if (Auth::id() !== $article->UserID && Auth::user()->type !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        // TODO: Delete associated photo from storage if exists
        // Storage::disk('public')->delete($article->{'Article Photo'});

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully!');
    }
}