{{-- resources/views/admin/skills/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Skill Name --}}
    <div class="col-12"> {{-- يأخذ العرض الكامل --}}
        <label for="Name" class="form-label">Skill Name <span class="text-danger">*</span></label>
        {{-- استخدام $skill->Name إذا كان المتغير مُمررًا (في حالة التعديل) --}}
        <input type="text" class="form-control @error('Name') is-invalid @enderror" id="Name" name="Name" value="{{ old('Name', $skill->Name ?? '') }}" required autofocus>
        @error('Name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Submit Button --}}
    <div class="col-12 text-end mt-3">
         <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary me-2">
            Cancel
        </a>
        <button type="submit" class="btn btn-success">
            {{-- التحقق إذا كان $skill موجودًا لتحديد نص الزر --}}
            {{ isset($skill) ? __('Update Skill') : __('Create Skill') }}
        </button>
    </div>
</div>