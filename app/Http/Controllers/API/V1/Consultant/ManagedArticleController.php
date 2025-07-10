<?php

namespace App\Http\Controllers\API\V1\Consultant;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- استيراد Storage Facade

class ManagedArticleController extends Controller
{
    // ... (Constructor - unchanged) ...

    /**
     * Display a listing of the resource managed by the consultant.
     * (No changes needed here for displaying paths, they are in the model data)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        // TODO: Add Authorization check - Ensure user is a Consultant
        if ($user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // eager load user only if needed for index list
        $articles = Article::where('UserID', $user->UserID)
                           ->latest()
                           ->paginate(15);

        // Consider ArticleResourceCollection - this is where you'd format paths if needed
        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage managed by the consultant.
     * Added file upload handling for photo and pdf.
     */
    public function store(Request $request)
    {
        $user = $request->user();
         // TODO: Add Authorization check - Ensure user is a Consultant
         if ($user->type !== 'خبير استشاري') {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

        $validatedData = $request->validate([
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Date' => 'required|date',
            'Type' => 'nullable|string|max:100',
            'Article_Photo' => 'nullable|image|max:2048', // Key changed to underscore
            'pdf_attachment' => 'nullable|mimes:pdf|max:10240',
        ]);

        // --- Handle File Uploads ---
        $articlePhotoPath = null;
        if ($request->hasFile('Article_Photo')) { // Key changed to underscore
            $articlePhotoPath = $request->file('Article_Photo')->store('articles/photos', 'public');
        }

        $pdfAttachmentPath = null;
        if ($request->hasFile('pdf_attachment')) {
             $pdfAttachmentPath = $request->file('pdf_attachment')->store('articles/pdfs', 'public');
        }
        // --- End File Uploads ---


        $article = Article::create([
            'UserID' => $user->UserID,
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Date' => $validatedData['Date'],
            'Type' => $validatedData['Type'] ?? null,
            'Article Photo' => $articlePhotoPath, // DB column has a space
            'pdf_attachment' => $pdfAttachmentPath,
        ]);

        return response()->json($article, 201);
    }

    /**
     * Display the specified resource managed by the consultant.
     * (No changes needed here for displaying paths, they are in the model data)
     */
    public function show(Request $request, $id) // Use ID, not route model binding yet for auth check
    {
        $user = $request->user();
        $article = Article::findOrFail($id);

        // Authorization: Check if article belongs to the consultant
        if ($article->UserID !== $user->UserID || $user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Consider ArticleResource - this is where you'd format paths if needed
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage managed by the consultant.
     * Added file upload handling for photo and pdf, including deletion of old files.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $article = Article::findOrFail($id);

        if ($article->UserID !== $user->UserID || $user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'Title' => 'string|max:255',
            'Description' => 'string',
            'Date' => 'date',
            'Type' => 'nullable|string|max:100',
            'Article_Photo' => 'nullable|image|max:2048',
            'pdf_attachment' => 'nullable|mimes:pdf|max:10240',
        ]);

        $updateData = [];

        if ($request->has('Title')) {
            $updateData['Title'] = $validatedData['Title'];
        }
        if ($request->has('Description')) {
            $updateData['Description'] = $validatedData['Description'];
        }
        if ($request->has('Date')) {
            $updateData['Date'] = $validatedData['Date'];
        }
        if ($request->has('Type')) {
            $updateData['Type'] = $validatedData['Type'];
        }

        if ($request->hasFile('Article_Photo')) {
            if ($article->{'Article Photo'}) {
                Storage::disk('public')->delete($article->{'Article Photo'});
            }
            $updateData['Article Photo'] = $request->file('Article_Photo')->store('articles/photos', 'public');
        }

        if ($request->hasFile('pdf_attachment')) {
            if ($article->pdf_attachment) {
                Storage::disk('public')->delete($article->pdf_attachment);
            }
            $updateData['pdf_attachment'] = $request->file('pdf_attachment')->store('articles/pdfs', 'public');
        }

        $article->update($updateData);

        return response()->json($article->fresh());
    }

    /**
     * Remove the specified resource from storage managed by the consultant.
     * Added deletion of associated files.
     */
    public function destroy(Request $request, $id) // Use ID
    {
        $user = $request->user();
        $article = Article::findOrFail($id);

         // Authorization: Check if article belongs to the consultant
        if ($article->UserID !== $user->UserID || $user->type !== 'خبير استشاري') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // --- Delete Associated Files ---
        if ($article->{'Article Photo'}) {
            Storage::disk('public')->delete($article->{'Article Photo'});
        }
        if ($article->pdf_attachment) {
             Storage::disk('public')->delete($article->pdf_attachment);
        }
        // --- End Delete Associated Files ---

        $article->delete();
        return response()->json(null, 204); // No Content
    }
}