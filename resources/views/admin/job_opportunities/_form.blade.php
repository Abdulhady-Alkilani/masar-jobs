{{-- resources/views/admin/job_opportunities/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Job Title --}}
    <div class="col-md-6">
        <label for="Job_Title" class="form-label">Job Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Job Title') is-invalid @enderror" id="Job_Title" name="Job Title" value="{{ old('Job Title', $jobOpportunity->{'Job Title'} ?? '') }}" required>
        @error('Job Title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Company (CompanyID) --}}
    <div class="col-md-6">
        <label for="CompanyID" class="form-label">Company <span class="text-danger">*</span></label>
        <select class="form-select @error('CompanyID') is-invalid @enderror" id="CompanyID" name="CompanyID" required>
            <option value="" selected disabled>-- Select Company --</option>
            {{-- $companies يتم تمريرها من دالة create/edit في الكنترولر --}}
            @foreach($companies ?? [] as $companyId => $companyName)
                <option value="{{ $companyId }}" {{ old('CompanyID', $jobOpportunity->CompanyID ?? '') == $companyId ? 'selected' : '' }}>
                    {{ $companyName }}
                </option>
            @endforeach
        </select>
        @error('CompanyID') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- User (UserID - Creator/Manager) --}}
    <div class="col-md-6">
        <label for="UserID" class="form-label">Posted By (Manager/Admin) <span class="text-danger">*</span></label>
        <select class="form-select @error('UserID') is-invalid @enderror" id="UserID" name="UserID" required>
            <option value="" selected disabled>-- Select User --</option>
             {{-- $managers يتم تمريرها من دالة create/edit في الكنترولر --}}
            @foreach($managers ?? [] as $managerId => $managerName)
                 {{-- في حالة التعديل، استخدمنا pluck('username', 'UserID') في المثال السابق --}}
                <option value="{{ $managerId }}" {{ old('UserID', $jobOpportunity->UserID ?? '') == $managerId ? 'selected' : '' }}>
                    {{ $managerName }}
                </option>
            @endforeach
        </select>
        @error('UserID') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Type --}}
    <div class="col-md-6">
        <label for="Type" class="form-label">Type <span class="text-danger">*</span></label>
        <select class="form-select @error('Type') is-invalid @enderror" id="Type" name="Type" required>
             <option value="" disabled {{ old('Type', $jobOpportunity->Type ?? '') == '' ? 'selected' : '' }}>-- Select Type --</option>
             <option value="وظيفة" {{ old('Type', $jobOpportunity->Type ?? '') == 'وظيفة' ? 'selected' : '' }}>وظيفة</option>
             <option value="تدريب" {{ old('Type', $jobOpportunity->Type ?? '') == 'تدريب' ? 'selected' : '' }}>تدريب</option>
        </select>
        @error('Type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Site (Location) --}}
    <div class="col-md-6">
        <label for="Site" class="form-label">Location / Site <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Site') is-invalid @enderror" id="Site" name="Site" value="{{ old('Site', $jobOpportunity->Site ?? '') }}" required>
        @error('Site') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- End Date --}}
    <div class="col-md-6">
        <label for="End_Date" class="form-label">Application End Date</label>
        <input type="date" class="form-control @error('End Date') is-invalid @enderror" id="End_Date" name="End Date" value="{{ old('End Date', $jobOpportunity->{'End Date'} ? \Carbon\Carbon::parse($jobOpportunity->{'End Date'})->format('Y-m-d') : '') }}">
        @error('End Date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Qualification --}}
    <div class="col-md-12">
        <label for="Qualification" class="form-label">Qualifications</label>
        <textarea class="form-control @error('Qualification') is-invalid @enderror" id="Qualification" name="Qualification" rows="3">{{ old('Qualification', $jobOpportunity->Qualification ?? '') }}</textarea>
        @error('Qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Skills --}}
    <div class="col-md-12">
        <label for="Skills" class="form-label">Required Skills <small class="text-muted">(Comma-separated)</small></label>
        <input type="text" class="form-control @error('Skills') is-invalid @enderror" id="Skills" name="Skills" value="{{ old('Skills', $jobOpportunity->Skills ?? '') }}">
        @error('Skills') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Job Description --}}
     <div class="col-md-12">
        <label for="Job_Description" class="form-label">Job Description <span class="text-danger">*</span></label>
        <textarea class="form-control @error('Job Description') is-invalid @enderror" id="Job_Description" name="Job Description" rows="5" required>{{ old('Job Description', $jobOpportunity->{'Job Description'} ?? '') }}</textarea>
        @error('Job Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

      {{-- Status --}}
    <div class="col-md-6">
        <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('Status') is-invalid @enderror" id="Status" name="Status" required>
            <option value="" disabled {{ old('Status', $jobOpportunity->Status ?? '') == '' ? 'selected' : '' }}>-- Select Status --</option>
            <option value="مفعل" {{ old('Status', $jobOpportunity->Status ?? 'مفعل') == 'مفعل' ? 'selected' : '' }}>Active</option>
            <option value="معلق" {{ old('Status', $jobOpportunity->Status ?? '') == 'معلق' ? 'selected' : '' }}>Pending / Inactive</option>
            <option value="محذوف" {{ old('Status', $jobOpportunity->Status ?? '') == 'محذوف' ? 'selected' : '' }}>Deleted</option>
        </select>
         @error('Status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Submit Button --}}
    <div class="col-12 text-end mt-3">
        <a href="{{ route('admin.job-opportunities.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i>
            {{ isset($jobOpportunity) ? __('Update Opportunity') : __('Create Opportunity') }}
        </button>
    </div>

</div>