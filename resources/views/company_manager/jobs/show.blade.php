@extends('layouts.company') {{-- استخدام layout مدير الشركة --}}
@section('title', ($company->Name ?? 'My Company') . ' - Profile')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-building me-2"></i> Company Profile: {{ $company->Name ?? 'N/A' }}
        </h2>
        @if($company)
            <a href="{{ route('company-manager.profile.edit') }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Profile
            </a>
        @endif
    </div>
@endsection

@section('content')
    @if($company)
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-info-circle me-2"></i>Company Information</span>
                <span class="badge {{ $company->Status === 'Approved' ? 'bg-success' : ($company->Status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                    Status: {{ $company->Status ?? 'N/A' }}
                </span>
            </div>
            <div class="card-body">
                @include('partials._alerts')
                <dl class="row">
                    <dt class="col-sm-3">Company Name:</dt>
                    <dd class="col-sm-9">{{ $company->Name }}</dd>

                    <dt class="col-sm-3">Managed By:</dt>
                    <dd class="col-sm-9">{{ $company->user->username ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Company Email:</dt>
                    <dd class="col-sm-9">{{ $company->Email ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Company Phone:</dt>
                    <dd class="col-sm-9">{{ $company->Phone ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Website:</dt>
                    <dd class="col-sm-9">
                        @if($company->{'Web site'})
                            <a href="{{ $company->{'Web site'} }}" target="_blank">{{ $company->{'Web site'} }} <i class="fas fa-external-link-alt fa-xs"></i></a>
                        @else
                            N/A
                        @endif
                    </dd>

                    <dt class="col-sm-3">Country:</dt>
                    <dd class="col-sm-9">{{ $company->Country ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">City:</dt>
                    <dd class="col-sm-9">{{ $company->City ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Detailed Address:</dt>
                    <dd class="col-sm-9">{{ $company->{'Detailed Address'} ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9 text-muted">{{ $company->Description ?? 'N/A' }}</dd>

                    {{-- أضف أي حقول أخرى هنا --}}
                </dl>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            Company profile not found or not yet approved.
            <a href="{{ route('company-manager.request.create') }}" class="alert-link">Click here to request company creation if you haven't already.</a>
        </div>
    @endif
@endsection