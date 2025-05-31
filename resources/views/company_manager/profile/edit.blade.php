@extends('layouts.company') {{-- استخدام layout مدير الشركة المُعد بـ Bootstrap --}}
@section('title', 'Edit Company Profile: ' . ($company->Name ?? ''))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-edit me-2"></i> Edit Company Profile: {{ $company->Name ?? 'N/A' }}
        </h2>
        @if($company)
            <a href="{{ route('company-manager.profile.show') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Profile
            </a>
        @endif
    </div>
@endsection

@section('content')
    @if($company)
        <div class="card shadow-sm">
            <div class="card-header">
                Update Your Company Information
            </div>
            <div class="card-body">
                @include('partials._alerts')

                <form action="{{ route('company-manager.profile.update') }}" method="POST" enctype="multipart/form-data"> {{-- المسار لا يحتاج لـ $company لأننا نجلبها من المستخدم --}}
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Company Name --}}
                        <div class="col-md-12">
                            <label for="Name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Name') is-invalid @enderror" id="Name" name="Name" value="{{ old('Name', $company->Name ?? '') }}" required>
                            @error('Name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

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
                        <div class="col-md-12">
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
                        <div class="col-12">
                            <label for="Detailed_Address" class="form-label">Detailed Address</label>
                            <textarea class="form-control @error('Detailed Address') is-invalid @enderror" id="Detailed_Address" name="Detailed Address" rows="3">{{ old('Detailed Address', $company->{'Detailed Address'} ?? '') }}</textarea>
                            @error('Detailed Address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="Description" class="form-label">Description</label>
                            <textarea class="form-control @error('Description') is-invalid @enderror" id="Description" name="Description" rows="5">{{ old('Description', $company->Description ?? '') }}</textarea>
                            @error('Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- يمكنك إضافة حقل لرفع شعار الشركة هنا --}}
                        {{--
                        <div class="col-12">
                            <label for="company_logo" class="form-label">Company Logo</label>
                            <input type="file" class="form-control @error('company_logo') is-invalid @enderror" id="company_logo" name="company_logo" accept="image/*">
                            @error('company_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($company->Media)
                                <img src="{{ asset('storage/' . $company->Media) }}" alt="Current Logo" class="img-thumbnail mt-2" style="max-width: 150px;">
                            @endif
                        </div>
                        --}}

                        {{-- Submit Buttons --}}
                        <div class="col-12 text-end mt-3">
                            <a href="{{ route('company-manager.profile.show') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Update Profile
                            </button>
                        </div>
                    </div> {{-- نهاية row g-3 --}}
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger text-center">
            Company profile not found. You cannot edit a profile that doesn't exist.
            Please <a href="{{ route('company-manager.request.create') }}" class="alert-link">request company creation</a> first.
        </div>
    @endif
@endsection