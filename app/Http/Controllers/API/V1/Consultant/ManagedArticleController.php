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
            // UserID is automatically set
            'Title' => 'required|string|max:255',
            'Description' => 'required|string',
            'Date' => 'required|date', // Or set automatically
            'Type' => 'nullable|string|max:100', // Consultant might set type
            // Validation for file uploads:
            'Article Photo' => 'nullable|image|max:2048', // Accepts image files, max 2MB
            'pdf_attachment' => 'nullable|mimes:pdf|max:10240', // Accepts PDF files, max 10MB
        ]);

        // --- Handle File Uploads ---
        $articlePhotoPath = null;
        if ($request->hasFile('Article Photo')) {
            // Store the file in storage/app/public/articles/photos
            // The store method generates a unique filename
            $articlePhotoPath = $request->file('Article Photo')->store('articles/photos', 'public');
             // We only store the path relative to the disk root ('articles/photos/...')
        }

        $pdfAttachmentPath = null;
        if ($request->hasFile('pdf_attachment')) {
             // Store the file in storage/app/public/articles/pdfs
             $pdfAttachmentPath = $request->file('pdf_attachment')->store('articles/pdfs', 'public');
             // We only store the path relative to the disk root ('articles/pdfs/...')
        }
        // --- End File Uploads ---


        $article = Article::create([
            'UserID' => $user->UserID,
            'Title' => $validatedData['Title'],
            'Description' => $validatedData['Description'],
            'Date' => $validatedData['Date'],
            'Type' => $validatedData['Type'] ?? null,
            'Article Photo' => $articlePhotoPath, // Save the stored path
            'pdf_attachment' => $pdfAttachmentPath, // Save the stored path
        ]);

        // Consider ArticleResource
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
    public function update(Request $request, $id) // Use ID
    {
        $user = $request->user();
        $article = Article::findOrFail($id);

        // Authorization: Check if article belongs to the consultant
        if ($article->UserID !== $user->UserID || $user->type !== 'خبير استشاري') {
             return response()->json(['message' => 'Unauthorized'], 403);
         }

         // Validate incoming data, including optional files
         $validatedData = $request->validate([
            'Title' => 'sometimes|required|string|max:255',
            'Description' => 'sometimes|required|string',
            'Date' => 'sometimes|required|date',
            'Type' => 'nullable|string|max:100',
            // Validation for optional file uploads/removal indicators:
            // For file uploads, accept the file or explicit null for removal
            'Article Photo' => 'nullable|image|max:2048', // New file or null
            'pdf_attachment' => 'nullable|mimes:pdf|max:10240', // New file or null
            // Note: To explicitly *remove* an existing file without uploading a new one,
            // the client should send null or an empty string for the file field.
        ]);

        // --- Handle File Updates ---
        // Update Article Photo
        if ($request->hasFile('Article Photo')) {
            // Delete old photo if it exists
            if ($article->{'Article Photo'}) {
                 Storage::disk('public')->delete($article->{'Article Photo'});
            }
             // Store the new photo
            $validatedData['Article Photo'] = $request->file('Article Photo')->store('articles/photos', 'public');
        } elseif ($request->has('Article Photo') && is_null($request->file('Article Photo'))) {
             // If client sent 'Article Photo': null, delete old photo and set column to null
             if ($article->{'Article Photo'}) {
                 Storage::disk('public')->delete($article->{'Article Photo'});
             }
             $validatedData['Article Photo'] = null;
        } else {
            // If 'Article Photo' is not in the request, don't update the column
            unset($validatedData['Article Photo']);
        }

         // Update PDF Attachment
         if ($request->hasFile('pdf_attachment')) {
            // Delete old pdf if it exists
             if ($article->pdf_attachment) {
                 Storage::disk('public')->delete($article->pdf_attachment);
             }
             // Store the new pdf
             $validatedData['pdf_attachment'] = $request->file('pdf_attachment')->store('articles/pdfs', 'public');
         } elseif ($request->has('pdf_attachment') && is_null($request->file('pdf_attachment'))) {
            // If client sent 'pdf_attachment': null, delete old pdf and set column to null
             if ($article->pdf_attachment) {
                 Storage::disk('public')->delete($article->pdf_attachment);
             }
             $validatedData['pdf_attachment'] = null;
         } else {
            // If 'pdf_attachment' is not in the request, don't update the column
             unset($validatedData['pdf_attachment']);
         }
         // --- End File Updates ---


        $article->update($validatedData);

         // Consider ArticleResource
        return response()->json($article); // Or $article->fresh() to get the latest data
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