{{-- resources/views/consultant/articles/_form.blade.php --}}
@csrf

<div class="row g-3"> {{-- استخدام Grid لتنظيم الحقول --}}

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
            {{-- حدد الأنواع المسموح للاستشاري باختيارها --}}
            <option value="استشاري" {{ old('Type', $article->Type ?? '') == 'استشاري' ? 'selected' : '' }}>استشاري</option>
            <option value="نصائح" {{ old('Type', $article->Type ?? '') == 'نصائح' ? 'selected' : '' }}>نصائح</option>
            {{-- قد تكون هناك أنواع أخرى --}}
         </select>
         @error('Type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Article Photo --}}
    <div class="col-md-6">
         <label for="Article_Photo" class="form-label">Article Photo</label>
         <input type="file" class="form-control @error('Article Photo') is-invalid @enderror" id="Article_Photo" name="Article Photo" accept="image/*">
        @error('Article Photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- عرض الصورة الحالية عند التعديل --}}
     @isset($article)
        @if ($article->{'Article Photo'})
            <div class="col-12 mb-2">
                 <label class="form-label d-block">Current Photo:</label>
                 <img src="{{ asset('storage/' . $article->{'Article Photo'}) }}" alt="Current Photo" class="img-thumbnail" style="max-height: 150px;">
                 {{-- يمكنك إضافة checkbox لحذف الصورة الحالية إذا أردت أن يتحكم الاستشاري بذلك --}}
                 {{-- <div class="form-check mt-2">
                      <input class="form-check-input" type="checkbox" name="delete_photo" id="delete_photo_consultant" value="1">
                      <label class="form-check-label" for="delete_photo_consultant">
                        Delete current photo (will be removed on update)
                      </label>
                    </div> --}}
            </div>
        @endif
     @endisset


    {{-- Description --}}
    <div class="col-12">
        <label for="Description" class="form-label">Description / Content <span class="text-danger">*</span></label>
        <textarea class="form-control @error('Description') is-invalid @enderror" id="Description" name="Description" rows="12" required>{{ old('Description', $article->Description ?? '') }}</textarea>
        @error('Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        {{-- Consider using a Rich Text Editor here --}}
    </div>


    {{-- Submit Buttons --}}
    <div class="col-12 text-end mt-3">
         {{-- رابط الإلغاء يشير دائمًا إلى قائمة مقالات الاستشاري --}}
         <a href="{{ route('consultant.articles.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i>
            {{-- النص يتغير بناءً على وجود $article (تعديل) أو عدمه (إنشاء) --}}
            {{ isset($article) ? 'Update Article' : 'Create Article' }}
        </button>
    </div>

</div>