{{-- resources/views/admin/companies/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Company Name --}}
    <div class="col-md-6">
        <label for="Name" class="form-label">Company Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('Name') is-invalid @enderror" id="Name" name="Name" value="{{ old('Name', $company->Name ?? '') }}" required>
        @error('Name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Manager (UserID) - فقط عند الإنشاء بواسطة الأدمن --}}
    {{-- في حالة التعديل، قد يكون من الأفضل عدم السماح بتغيير المدير بسهولة --}}
    @if(!isset($company))
        <div class="col-md-6">
            <label for="UserID" class="form-label">Assign Manager <span class="text-danger">*</span></label>
            <select class="form-select @error('UserID') is-invalid @enderror" id="UserID" name="UserID" required>
                <option value="" selected disabled>-- Select Manager --</option>
                @foreach($managers ?? [] as $managerId => $managerName)
                    <option value="{{ $managerId }}" {{ old('UserID') == $managerId ? 'selected' : '' }}>{{ $managerName }}</option>
                @endforeach
            </select>
            @error('UserID') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if(!isset($managers) || $managers->isEmpty()) <small class="text-warning d-block mt-1">No available managers found (either all assigned or none exist).</small> @endif
        </div>
    @else
         <div class="col-md-6">
            <label class="form-label">Assigned Manager</label>
            <input type="text" class="form-control" value="{{ $company->user->username ?? 'N/A' }}" disabled readonly>
            {{-- يمكنك إضافة رابط لعرض المستخدم المدير --}}
            @if($company->user) <a href="{{route('admin.users.show', $company->user)}}" class="btn btn-sm btn-link p-0 mt-1">View Manager</a> @endif
        </div>
    @endif


    {{-- Email --}}
    <div class="col-md-6">
        <label for="Email" class="form-label">Company Email</label>
        <input type="email" class="form-control @error('Email') is-invalid @enderror" id="Email" name="Email" value="{{ old('Email', $company->Email ?? '') }}">
        @error('Email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Phone --}}
    <div class="col-md-6">
        <label for="Phone" class="form-label">Company Phone</label>
        <input type="tel" class="form-control @error('Phone') is-invalid @enderror" id="Phone" name="Phone" value="{{ old('Phone', $company->Phone ?? '') }}">
        @error('Phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Website --}}
    <div class="col-md-6">
        <label for="Web_site" class="form-label">Website</label>
        <input type="url" class="form-control @error('Web site') is-invalid @enderror" id="Web_site" name="Web site" value="{{ old('Web site', $company->{'Web site'} ?? '') }}" placeholder="https://example.com">
        @error('Web site') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Country --}}
     <div class="col-md-6">
        <label for="Country" class="form-label">Country</label>
        <input type="text" class="form-control @error('Country') is-invalid @enderror" id="Country" name="Country" value="{{ old('Country', $company->Country ?? '') }}">
        @error('Country') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- City --}}
     <div class="col-md-6">
        <label for="City" class="form-label">City</label>
        <input type="text" class="form-control @error('City') is-invalid @enderror" id="City" name="City" value="{{ old('City', $company->City ?? '') }}">
         @error('City') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Detailed Address --}}
     <div class="col-md-12">
        <label for="Detailed_Address" class="form-label">Detailed Address</label>
        <textarea class="form-control @error('Detailed Address') is-invalid @enderror" id="Detailed_Address" name="Detailed Address" rows="3">{{ old('Detailed Address', $company->{'Detailed Address'} ?? '') }}</textarea>
        @error('Detailed Address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Description --}}
     <div class="col-md-12">
        <label for="Description" class="form-label">Description</label>
        <textarea class="form-control @error('Description') is-invalid @enderror" id="Description" name="Description" rows="5">{{ old('Description', $company->Description ?? '') }}</textarea>
        @error('Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Status (Admin can set it) --}}
    <div class="col-md-6">
        <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('Status') is-invalid @enderror" id="Status" name="Status" required>
           <option value="" disabled {{ old('Status', $company->Status ?? '') == '' ? 'selected' : '' }}>-- Select Status --</option>
           <option value="Approved" {{ old('Status', $company->Status ?? 'Approved') == 'Approved' ? 'selected' : '' }}>Approved / Active</option>
           <option value="Pending" {{ old('Status', $company->Status ?? '') == 'Pending' ? 'selected' : '' }}>Pending Approval</option>
           <option value="Rejected" {{ old('Status', $company->Status ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
           {{-- أضف حالات أخرى إذا لزم الأمر --}}
        </select>
         @error('Status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- يمكنك إضافة حقول أخرى هنا مثل رفع الشعار إلخ --}}

     {{-- Submit Button --}}
    <div class="col-12 text-end mt-3">
         <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary me-2">
             Cancel
         </a>
        <button type="submit" class="btn btn-success">
             <i class="fas fa-save me-1"></i>
            {{ isset($company) ? __('Update Company') : __('Create Company') }}
        </button>
    </div>

</div>