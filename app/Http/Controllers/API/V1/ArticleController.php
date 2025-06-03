<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource. (Public)
     */
    public function index(Request $request)
    {
        // TODO: Add filtering (by type, user?), sorting, pagination
        $articles = Article::with('user:UserID,first_name,last_name') // Eager load user name
                           ->latest() // Order by newest first
                           ->paginate(15); // Example pagination

        // Consider using an ArticleResourceCollection
        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage. (Admin Only?)
     */
    public function store(Request $request)
    {
        // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        // if (Auth::user()->type !== 'Admin') {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $validatedData = $request->validate([
            'UserID' => 'required|exists:users,UserID', // Admin assigns user
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Date' => 'required|date',
            'Type' => 'nullable|string|max:100',
            'Article Photo' => 'nullable|string|max:255', // Or handle file upload
        ]);

        $article = Article::create($validatedData);

        // Consider using ArticleResource
        return response()->json($article, 201);
    }

    /**
     * Display the specified resource. (Public)
     */
    public function show($id) // Route model binding: Article $article
    {
        $article = Article::with('user:UserID,first_name,last_name')->findOrFail($id);
        // Consider using ArticleResource
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage. (Admin Only?)
     */
    public function update(Request $request, $id) // Route model binding: Article $article
    {
         // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        $article = Article::findOrFail($id);

        $validatedData = $request->validate([
            'UserID' => 'sometimes|required|exists:users,UserID',
            'Title' => 'sometimes|required|string|max:255',
            'Description' => 'sometimes|required|string',
            'Date' => 'sometimes|required|date',
            'Type' => 'nullable|string|max:100',
            'Article Photo' => 'nullable|string|max:255', // Or handle file upload
        ]);

        $article->update($validatedData);

        // Consider using ArticleResource
        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage. (Admin Only?)
     */
    public function destroy($id) // Route model binding: Article $article
    {
         // TODO: Add Authorization check (Gate/Policy) - Only Admin?
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204); // No Content
    }
}