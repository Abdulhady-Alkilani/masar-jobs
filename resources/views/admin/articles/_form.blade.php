{{-- Partial form for creating and editing articles (using Bootstrap) --}}
@csrf

{{-- Author Selection (Admin Only potentially) --}}
@if(isset($authors)) {{-- عرض هذا الحقل فقط إذا تم تمرير قائمة المؤلفين --}}
<div class="mb-3">
    <label for="UserID" class="form-label">Author <span class="text-danger">*</span></label>
    <select name="UserID" id="UserID" required class="form-select @error('UserID') is-invalid @enderror">
        <option value="">-- Select Author --</option>
        @foreach($authors as $authorId => $authorName)
            <option value="{{ $authorId }}" {{ old('UserID', $article->UserID ?? '') == $authorId ? 'selected' : '' }}>
                {{ $authorName }}
            </option>
        @endforeach
    </select>
    @error('UserID') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
@else
    {{-- إذا لم يتم تمرير المؤلفين (مثل الاستشاري يعدل مقاله)، يمكن إخفاء الحقل أو تمرير ID المؤلف الحالي --}}
    <input type="hidden" name="UserID" value="{{ $article->UserID ?? Auth::id() }}">
@endif


{{-- Title --}}
<div class="mb-3">
    <label for="Title" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
    <input id="Title" type="text" name="Title" value="{{ old('Title', $article->Title ?? '') }}" required
           class="form-control @error('Title') is-invalid @enderror">
    @error('Title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Type --}}
<div class="mb-3">
     <label for="Type" class="form-label">{{ __('Type') }} <span class="text-danger">*</span></label>
     <select name="Type" id="Type" required class="form-select @error('Type') is-invalid @enderror">
        <option value="" disabled {{ old('Type', $article->Type ?? '') == '' ? 'selected' : '' }}>{{ __('-- Select Type --') }}</option>
        <option value="استشاري" {{ old('Type', $article->Type ?? '') == 'استشاري' ? 'selected' : '' }}>استشاري</option>
        <option value="نصائح" {{ old('Type', $article->Type ?? '') == 'نصائح' ? 'selected' : '' }}>نصائح</option>
     </select>
     @error('Type') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="mb-3">
    <label for="Description" class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
    <textarea id="Description" name="Description" rows="10" required
              class="form-control @error('Description') is-invalid @enderror">{{ old('Description', $article->Description ?? '') }}</textarea>
    @error('Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Article Photo --}}
<div class="mb-3">
     <label for="Article_Photo" class="form-label">{{ __('Article Photo') }}</label>
     {{-- استخدم اسم الحقل المتوقع في التحقق من الصحة --}}
     <input id="Article_Photo" type="file" name="Article Photo" accept="image/*"
            class="form-control @error('Article Photo') is-invalid @enderror">
    @error('Article Photo') <div class="invalid-feedback">{{ $message }}</div> @enderror

    {{-- عرض الصورة الحالية عند التعديل --}}
     @isset($article)
        @if ($article->{'Article Photo'})
            <div class="mt-3">
                 <label class="form-label d-block">Current Photo:</label>
                 <img src="{{ asset('storage/' . $article->{'Article Photo'}) }}" alt="Current Photo" class="img-thumbnail" style="max-height: 150px;">
            </div>
        @endif
     @endisset
</div>

{{-- Submit Button --}}
<div class="d-flex justify-content-end mt-4">
     <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary me-2">
        {{ __('Cancel') }}
    </a>
    <button type="submit" class="btn btn-success">
        {{ isset($article) ? __('Update Article') : __('Create Article') }}
    </button>
</div>