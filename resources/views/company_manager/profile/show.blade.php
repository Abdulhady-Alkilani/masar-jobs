@extends('layouts.company') {{-- تأكد من أنه يرث من layout مدير الشركة --}}
@section('title', ($company->Name ?? 'My Company') . ' - Profile')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-building me-2"></i> Company Profile: {{ $company->Name ?? 'N/A' }}
        </h2>
        @if($company) {{-- زر التعديل يظهر فقط إذا كانت الشركة موجودة --}}
            <a href="{{ route('company-manager.profile.edit') }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Profile
            </a>
        @endif
    </div>
@endsection

@section('content')
    {{-- لا نحتاج container هنا لأن الـ Layout يوفر padding --}}
    @include('partials._alerts')

    @if($company)
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-info-circle me-2"></i>Company Information</span>
                <span class="badge {{ $company->Status === 'Approved' ? 'bg-success' : ($company->Status === 'Pending' ? 'bg-warning text-dark' : ($company->Status === 'Rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                    Status: {{ $company->Status ?? 'N/A' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block text-muted">Company Name:</strong>
                        <p>{{ $company->Name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block text-muted">Managed By (User):</strong>
                        <p>{{ $company->user->username ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block text-muted">Company Email:</strong>
                        <p>{{ $company->Email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block text-muted">Company Phone:</strong>
                        <p>{{ $company->Phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                     <div class="col-md-6">
                        <strong class="d-block text-muted">Website:</strong>
                        <p>
                            @if($company->{'Web site'})
                                <a href="{{ $company->{'Web site'} }}" target="_blank">{{ $company->{'Web site'} }} <i class="fas fa-external-link-alt fa-xs"></i></a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block text-muted">Country:</strong>
                        <p>{{ $company->Country ?? 'N/A' }}</p>
                    </div>
                </div>

                 <div class="row mb-3">
                    <div class="col-md-6">
                        <strong class="d-block text-muted">City:</strong>
                        <p>{{ $company->City ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong class="d-block text-muted">Detailed Address:</strong>
                        <p>{{ $company->{'Detailed Address'} ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong class="d-block text-muted">Description:</strong>
                    <p class="text-body-secondary" style="white-space: pre-wrap;">{{ $company->Description ?? 'N/A' }}</p>
                </div>

                {{-- يمكنك إضافة أي حقول أخرى هنا بنفس الطريقة --}}
                {{--
                <hr class="my-3">
                <div>
                    <strong class="d-block text-muted">Company Logo:</strong>
                    @if($company->Media)
                        <img src="{{ asset('storage/' . $company->Media) }}" alt="{{ $company->Name }} Logo" class="img-thumbnail mt-2" style="max-height: 150px;">
                    @else
                        <p class="text-muted">No logo uploaded.</p>
                    @endif
                </div>
                --}}
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center">
            <h4 class="alert-heading">Company Profile Not Found!</h4>
            <p>It seems your company profile has not been created or is still pending approval.</p>
            <hr>
            <p class="mb-0">
                If you haven't submitted a request yet, you can
                <a href="{{ route('company-manager.request.create') }}" class="alert-link">request company creation here</a>.
                Otherwise, please wait for administrator approval.
            </p>
        </div>
    @endif
@endsection