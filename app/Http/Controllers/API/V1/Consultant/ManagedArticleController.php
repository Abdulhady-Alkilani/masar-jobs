<?php

namespace App\Http\Controllers\API\V1\Consultant; // Correct Namespace

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagedArticleController extends Controller
{
    // --- Assuming Consultants can manage their own articles ---
    // Logic is very similar to Company/ManagedJobOpportunityController
    // but based on Article model and consultant's UserID

    /**
     * Display a listing of the resource managed by the consultant.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        // TODO: Add Authorization check - Ensure user is a Consultant
        if ($user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $articles = Article::where('UserID', $user->UserID)
                           ->latest()
                           ->paginate(15);

        // Consider ArticleResourceCollection
        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage managed by the consultant.
     */
    public function store(Request $request)
    {
        $user = $request->user();
         // TODO: Add Authorization check - Ensure user is a Consultant
         if ($user->type !== 'خبير استشاري') {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

        $validatedData = $request->validate([
            // UserID is automatically set
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Date' => 'required|date', // Or set automatically
            'Type' => 'nullable|string|max:100', // Consultant might set type
            'Article Photo' => 'nullable|string|max:255', // Handle upload
        ]);

        $validatedData['UserID'] = $user->UserID;
        // $validatedData['Date'] = $validatedData['Date'] ?? now(); // Set date automatically?

        $article = Article::create($validatedData);
        // Consider ArticleResource
        return response()->json($article, 201);
    }

    /**
     * Display the specified resource managed by the consultant.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $article = Article::findOrFail($id);

        // Authorization: Check if article belongs to the consultant
        if ($article->UserID !== $user->UserID || $user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Consider ArticleResource
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage managed by the consultant.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $article = Article::findOrFail($id);

        // Authorization: Check if article belongs to the consultant
        if ($article->UserID !== $user->UserID || $user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

         $validatedData = $request->validate([
            'Title' => 'sometimes|required|string|max:255',
            'Description' => 'sometimes|required|string',
            'Date' => 'sometimes|required|date',
            'Type' => 'nullable|string|max:100',
            'Article Photo' => 'nullable|string|max:255',
        ]);

        $article->update($validatedData);
         // Consider ArticleResource
        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage managed by the consultant.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $article = Article::findOrFail($id);

         // Authorization: Check if article belongs to the consultant
        if ($article->UserID !== $user->UserID || $user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $article->delete();
        return response()->json(null, 204);
    }
}