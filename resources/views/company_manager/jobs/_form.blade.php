{{-- resources/views/company_manager/jobs/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Job Title --}}
    <div class="col-md-12">
        <label for="Job_Title" class="form-label">Job Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Job Title') is-invalid @enderror" id="Job_Title" name="Job Title" value="{{ old('Job Title', $jobOpportunity->{'Job Title'} ?? '') }}" required>
        @error('Job Title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Type --}}
    <div class="col-md-6">
        <label for="Type" class="form-label">Opportunity Type <span class="text-danger">*</span></label>
        <select class="form-select @error('Type') is-invalid @enderror" id="Type" name="Type" required>
             <option value="" disabled {{ old('Type', $jobOpportunity->Type ?? '') == '' ? 'selected' : '' }}>-- Select Type --</option>
             <option value="وظيفة" {{ old('Type', $jobOpportunity->Type ?? '') == 'وظيفة' ? 'selected' : '' }}>وظيفة (Job)</option>
             <option value="تدريب" {{ old('Type', $jobOpportunity->Type ?? '') == 'تدريب' ? 'selected' : '' }}>تدريب (Training)</option>
        </select>
        @error('Type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Site (Location) --}}
    <div class="col-md-6">
        <label for="Site" class="form-label">Location / Site <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Site') is-invalid @enderror" id="Site" name="Site" value="{{ old('Site', $jobOpportunity->Site ?? '') }}" required placeholder="e.g., Riyadh, Remote, On-site">
        @error('Site') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- End Date --}}
    <div class="col-md-6">
        <label for="End_Date" class="form-label">Application End Date</label>
        <input type="date" class="form-control @error('End Date') is-invalid @enderror" id="End_Date" name="End Date" value="{{ old('End Date', $jobOpportunity->{'End Date'} ? \Carbon\Carbon::parse($jobOpportunity->{'End Date'})->format('Y-m-d') : '') }}">
        @error('End Date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Status (Manager can set it for their jobs) --}}
    <div class="col-md-6">
        <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('Status') is-invalid @enderror" id="Status" name="Status" required>
            <option value="مفعل" {{ old('Status', $jobOpportunity->Status ?? 'مفعل') == 'مفعل' ? 'selected' : '' }}>Active (مفعل)</option>
            <option value="معلق" {{ old('Status', $jobOpportunity->Status ?? '') == 'معلق' ? 'selected' : '' }}>Inactive/Paused (معلق)</option>
            {{-- مدير الشركة قد لا يحتاج لخيار "محذوف" هنا، الحذف يتم بزر منفصل --}}
        </select>
         @error('Status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>


     {{-- Qualification --}}
    <div class="col-md-12">
        <label for="Qualification" class="form-label">Qualifications</label>
        <textarea class="form-control @error('Qualification') is-invalid @enderror" id="Qualification" name="Qualification" rows="3">{{ old('Qualification', $jobOpportunity->Qualification ?? '') }}</textarea>
        @error('Qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Skills --}}
    <div class="col-md-12">
        <label for="Skills" class="form-label">Required Skills <small class="text-muted">(Comma-separated, or integrate with skills system)</small></label>
        <input type="text" class="form-control @error('Skills') is-invalid @enderror" id="Skills" name="Skills" value="{{ old('Skills', $jobOpportunity->Skills ?? '') }}">
        @error('Skills') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Job Description --}}
     <div class="col-md-12">
        <label for="Job_Description" class="form-label">Job Description <span class="text-danger">*</span></label>
        <textarea class="form-control @error('Job Description') is-invalid @enderror" id="Job_Description" name="Job Description" rows="5" required>{{ old('Job Description', $jobOpportunity->{'Job Description'} ?? '') }}</textarea>
        @error('Job Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Submit Button --}}
    <div class="col-12 text-end mt-3">
        <a href="{{ route('company-manager.job-opportunities.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i>
            {{ isset($jobOpportunity) ? __('Update Opportunity') : __('Create Opportunity') }}
        </button>
    </div>
</div>