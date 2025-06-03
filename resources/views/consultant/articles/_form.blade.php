{{-- resources/views/consultant/articles/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Title --}}
    <div class="col-12">
        <label for="Title" class="form-label">Article Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Title') is-invalid @enderror" id="Title" name="Title" value="{{ old('Title', $article->Title ?? '') }}" required autofocus>
        @error('Title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Type --}}
    <div class="col-md-6">
         <label for="Type" class="form-label">Article Type <span class="text-danger">*</span></label>
         <select name="Type" id="Type" required class="form-select @error('Type') is-invalid @enderror">
            <option value="" disabled {{ old('Type', $article->Type ?? '') == '' ? 'selected' : '' }}>-- Select Type --</option>
            <option value="استشاري" {{ old('Type', $article->Type ?? '') == 'استشاري' ? 'selected' : '' }}>استشاري</option>
            <option value="نصائح" {{ old('Type', $article->Type ?? '') == 'نصائح' ? 'selected' : '' }}>نصائح</option>
         </select>
         @error('Type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Article Photo --}}
    <div class="col-md-6">
         <label for="Article_Photo" class="form-label">Article Photo</label>
         <input type="file" class="form-control @error('Article Photo') is-invalid @enderror" id="Article_Photo" name="Article Photo" accept="image/*">
        @error('Article Photo') <div class="invalid-feedback">{{ $message }}</div> @enderror

        @isset($article)
            @if ($article->{'Article Photo'} && Storage::disk('public')->exists($article->{'Article Photo'}))
                <div class="mt-2">
                    <img src="{{ Storage::disk('public')->url($article->{'Article Photo'}) }}" class="img-thumbnail" style="max-height: 100px;" alt="Current Photo">
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="delete_current_photo" id="delete_current_photo" value="1">
                        <label class="form-check-label small text-muted" for="delete_current_photo">Delete current photo</label>
                    </div>
                </div>
            @endif
        @endisset
    </div>

    {{-- !!! إضافة حقل ملف PDF !!! --}}
    <div class="col-md-12"> {{-- أو col-md-6 إذا أردت وضع حقل آخر بجانبه --}}
        <label for="pdf_attachment" class="form-label">PDF Attachment <small class="text-muted">(Max 10MB, PDF only)</small></label>
        <input type="file" class="form-control @error('pdf_attachment') is-invalid @enderror" id="pdf_attachment" name="pdf_attachment" accept="application/pdf">
        @error('pdf_attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror

        @isset($article)
            @if ($article->pdf_attachment && Storage::disk('public')->exists($article->pdf_attachment))
                <div class="mt-2">
                    <a href="{{ Storage::disk('public')->url($article->pdf_attachment) }}" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-file-pdf me-1"></i> View Current PDF
                    </a>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="delete_current_pdf" id="delete_current_pdf" value="1">
                        <label class="form-check-label small text-muted" for="delete_current_pdf">Delete current PDF</label>
                    </div>
                </div>
            @endif
        @endisset
    </div>
    {{-- !!! نهاية إضافة حقل ملف PDF !!! --}}


    {{-- Description --}}
    <div class="col-12">
        <label for="Description" class="form-label">Description / Content <span class="text-danger">*</span></label>
        <textarea class="form-control @error('Description') is-invalid @enderror" id="Description" name="Description" rows="12" required>{{ old('Description', $article->Description ?? '') }}</textarea>
        @error('Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>


    {{-- Submit Buttons --}}
    <div class="col-12 text-end mt-3">
         <a href="{{ route('consultant.articles.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i>
            {{ isset($article) ? 'Update Article' : 'Create Article' }}
        </button>
    </div>

</div>