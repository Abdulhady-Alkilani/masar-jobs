@extends('layouts.company') {{-- تأكد أنه يرث من layout مدير الشركة المُعد بـ Bootstrap --}}

@section('title', 'Company Dashboard')

@section('header')
    <h2 class="h4 mb-0 text-primary">
        <i class="fas fa-tachometer-alt me-2"></i> {{ __('Company Manager Dashboard') }}
    </h2>
@endsection

@section('content')
    {{-- لا نحتاج container هنا لأن الـ Layout يوفر padding --}}
    @include('partials._alerts')

    {{-- قسم الترحيب ومعلومات الشركة --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body {{ $company && $company->Status === 'Approved' ? 'bg-light-subtle' : 'bg-warning-subtle' }} rounded"> {{-- تغيير الخلفية حسب حالة الشركة --}}
            @if($company)
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">
                            Welcome, {{ Auth::user()->first_name }}!
                            <span class="ms-2 badge {{ $company->Status === 'Approved' ? 'bg-success' : ($company->Status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                Company Status: {{ $company->Status }}
                            </span>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">Managing: <strong>{{ $company->Name }}</strong></h6>
                        <p class="card-text text-muted small mb-2">
                            Quick access to manage your company's profile, job opportunities, and applications.
                        </p>
                    </div>
                    <a href="{{ route('company-manager.profile.show') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-building me-1"></i> View/Edit Profile
                    </a>
                </div>
                 @if($company->Status !== 'Approved')
                    <p class="mt-2 mb-0 small text-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Your company profile is currently <strong>{{ $company->Status }}</strong>.
                        @if($company->Status === 'Pending')
                            It's awaiting administrator approval. Some features might be limited.
                        @elseif($company->Status === 'Rejected')
                             It has been rejected. Please contact support for more information.
                        @endif
                    </p>
                 @endif
            @else
                <h5 class="card-title text-warning">Company Profile Not Found or Pending Creation</h5>
                <p class="card-text text-muted">
                    Your company profile might be pending approval or hasn't been created yet.
                    <a href="{{ route('company-manager.request.create') }}" class="text-primary">Click here to request company creation.</a>
                </p>
            @endif
        </div>
    </div>

    {{-- قسم الإحصائيات (يظهر فقط إذا كانت الشركة معتمدة) --}}
    @if($company && $company->Status === 'Approved')
        <div class="row g-3 mb-4">
            {{-- Stat: Active Jobs --}}
            <div class="col-md-6 col-xl-4">
                <div class="card text-center h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                        <div class="mb-2"><i class="fas fa-briefcase fa-2x text-primary"></i></div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">Active Jobs</h6>
                        <p class="card-text h3 fw-bold text-primary mb-0">{{ $stats['active_jobs'] ?? 0 }}</p>
                        <a href="{{ route('company-manager.job-opportunities.index') }}" class="stretched-link" aria-label="Manage Jobs"></a>
                    </div>
                </div>
            </div>
            {{-- Stat: Total Applications --}}
            <div class="col-md-6 col-xl-4">
                <div class="card text-center h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                        <div class="mb-2"><i class="fas fa-users fa-2x text-success"></i></div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">Total Applications</h6>
                        <p class="card-text h3 fw-bold text-success mb-0">{{ $stats['total_applications'] ?? 0 }}</p>
                        <a href="{{ route('company-manager.job-applications.index') }}" class="stretched-link" aria-label="View Applications"></a>
                    </div>
                </div>
            </div>
            {{-- Stat: Active Courses (إذا كانت الشركة تنشر دورات) --}}
             @if(isset($stats['active_courses']))
                <div class="col-md-6 col-xl-4">
                    <div class="card text-center h-100 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-center py-4">
                            <div class="mb-2"><i class="fas fa-graduation-cap fa-2x text-info"></i></div>
                            <h6 class="card-title text-muted small text-uppercase mb-1">Active Courses</h6>
                            <p class="card-text h3 fw-bold text-info mb-0">{{ $stats['active_courses'] ?? 0 }}</p>
                            <a href="{{ route('company-manager.training-courses.index') }}" class="stretched-link" aria-label="Manage Courses"></a>
                        </div>
                    </div>
                </div>
            @endif
            {{-- أضف إحصائيات أخرى إذا لزم الأمر --}}
        </div>

        {{-- يمكنك إضافة أقسام للأنشطة الأخيرة هنا (مثل آخر الوظائف المضافة، آخر المتقدمين) --}}
        {{--
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-history me-2"></i> Recent Activity
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item">... Recent Job Posted ...</div>
                <div class="list-group-item">... New Application Received ...</div>
            </div>
        </div>
        --}}
    @endif
@endsection