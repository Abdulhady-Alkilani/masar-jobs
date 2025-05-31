{{-- resources/views/admin/training_courses/_form.blade.php --}}
@csrf {{-- هذا يمكن أن يكون هنا أو في النموذج الرئيسي --}}

<div class="row g-3">

    {{-- Creator Selection (UserID - Admin selects) --}}
    <div class="col-md-6">
        <label for="UserID" class="form-label">Course Creator <span class="text-danger">*</span></label>
        <select name="UserID" id="UserID" required class="form-select @error('UserID') is-invalid @enderror">
            <option value="">-- Select Creator --</option>
            @foreach($creators ?? [] as $creatorId => $creatorName)
                <option value="{{ $creatorId }}" {{ old('UserID', $trainingCourse->UserID ?? '') == $creatorId ? 'selected' : '' }}>
                    {{ $creatorName }} (ID: {{ $creatorId }})
                </option>
            @endforeach
        </select>
        @error('UserID') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Course Name --}}
    <div class="col-md-6">
        <label for="Course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Course name') is-invalid @enderror" id="Course_name" name="Course name" value="{{ old('Course name', $trainingCourse->{'Course name'} ?? '') }}" required>
        @error('Course name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Trainers Name --}}
    <div class="col-md-6">
        <label for="Trainers_name" class="form-label">Trainer(s) Name</label>
        <input type="text" class="form-control @error('Trainers name') is-invalid @enderror" id="Trainers_name" name="Trainers name" value="{{ old('Trainers name', $trainingCourse->{'Trainers name'} ?? '') }}">
        @error('Trainers name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Trainers Site/Link --}}
    <div class="col-md-6">
        <label for="Trainers_Site" class="form-label">Trainer(s) Website/Link</label>
        <input type="url" class="form-control @error('Trainers Site') is-invalid @enderror" id="Trainers_Site" name="Trainers Site" value="{{ old('Trainers Site', $trainingCourse->{'Trainers Site'} ?? '') }}" placeholder="https://...">
        @error('Trainers Site') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Site (Location/Platform) --}}
    <div class="col-md-6">
        <label for="Site" class="form-label">Location / Platform <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Site') is-invalid @enderror" id="Site" name="Site" value="{{ old('Site', $trainingCourse->Site ?? '') }}" required placeholder="e.g., Online, Riyadh Center, Zoom">
        @error('Site') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Enrollment Link --}}
    <div class="col-md-6">
        <label for="Enroll_Hyper_Link" class="form-label">Enrollment Link</label>
        <input type="url" class="form-control @error('Enroll Hyper Link') is-invalid @enderror" id="Enroll_Hyper_Link" name="Enroll Hyper Link" value="{{ old('Enroll Hyper Link', $trainingCourse->{'Enroll Hyper Link'} ?? '') }}" placeholder="https://...">
        @error('Enroll Hyper Link') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Stage --}}
    <div class="col-md-4">
        <label for="Stage" class="form-label">Level (Stage) <span class="text-danger">*</span></label>
        <select class="form-select @error('Stage') is-invalid @enderror" id="Stage" name="Stage" required>
            <option value="" disabled {{ old('Stage', $trainingCourse->Stage ?? '') == '' ? 'selected' : '' }}>-- Select Level --</option>
            <option value="مبتدئ" {{ old('Stage', $trainingCourse->Stage ?? '') == 'مبتدئ' ? 'selected' : '' }}>مبتدئ (Beginner)</option>
            <option value="متوسط" {{ old('Stage', $trainingCourse->Stage ?? '') == 'متوسط' ? 'selected' : '' }}>متوسط (Intermediate)</option>
            <option value="متقدم" {{ old('Stage', $trainingCourse->Stage ?? '') == 'متقدم' ? 'selected' : '' }}>متقدم (Advanced)</option>
        </select>
        @error('Stage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Certificate --}}
    <div class="col-md-4">
        <label for="Certificate" class="form-label">Certificate <span class="text-danger">*</span></label>
        <select class="form-select @error('Certificate') is-invalid @enderror" id="Certificate" name="Certificate" required>
             <option value="" disabled {{ old('Certificate', $trainingCourse->Certificate ?? '') == '' ? 'selected' : '' }}>-- Select Option --</option>
             <option value="يوجد" {{ old('Certificate', $trainingCourse->Certificate ?? '') == 'يوجد' ? 'selected' : '' }}>يوجد (Yes)</option>
             <option value="لا يوجد" {{ old('Certificate', $trainingCourse->Certificate ?? '') == 'لا يوجد' ? 'selected' : '' }}>لا يوجد (No)</option>
        </select>
        @error('Certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Start Date --}}
    <div class="col-md-6">
        <label for="Start_Date" class="form-label">Start Date</label>
        <input type="date" class="form-control @error('Start Date') is-invalid @enderror" id="Start_Date" name="Start Date" value="{{ old('Start Date', optional($trainingCourse->{'Start Date'})->format('Y-m-d')) }}">
        @error('Start Date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- End Date --}}
     <div class="col-md-6">
        <label for="End_Date" class="form-label">End Date</label>
        <input type="date" class="form-control @error('End Date') is-invalid @enderror" id="End_Date" name="End Date" value="{{ old('End Date', optional($trainingCourse->{'End Date'})->format('Y-m-d')) }}">
        @error('End Date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Course Description --}}
    <div class="col-12">
        <label for="Course_Description" class="form-label">Course Description <span class="text-danger">*</span></label>
        <textarea class="form-control @error('Course Description') is-invalid @enderror" id="Course_Description" name="Course Description" rows="6" required>{{ old('Course Description', $trainingCourse->{'Course Description'} ?? '') }}</textarea>
        @error('Course Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Submit Button --}}
    <div class="col-12 text-end mt-3">
         <a href="{{ route('admin.training-courses.index') }}" class="btn btn-secondary me-2">
            Cancel
        </a>
        <button type="submit" class="btn btn-success">
            {{ isset($trainingCourse) ? __('Update Course') : __('Create Course') }}
        </button>
    </div>

</div>