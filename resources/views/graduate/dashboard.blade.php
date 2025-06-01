{{-- resources/views/graduate/dashboard.blade.php --}}
@extends('layouts.graduate')
@section('title', 'Graduate Dashboard')

@section('header')
    <h2 class="h4 mb-0 text-primary"><i class="fas fa-user-graduate me-2"></i> Graduate Dashboard</h2>
@endsection

@section('content')
    @include('partials._alerts')

    {{-- قسم الترحيب والروابط السريعة --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded p-4">
            <h5 class="card-title">Welcome back, {{ $user->first_name ?? Auth::user()->first_name ?? 'Graduate' }}!</h5>
            <p class="card-text text-muted mb-3">
                Here's a quick overview of your journey. Keep exploring opportunities and developing your skills!
            </p>
             <div class="d-flex flex-wrap gap-2">
                {{-- هذا الرابط يؤدي إلى GraduateProfileController@show الذي يعرض profile.show.blade.php --}}
                <a href="{{ route('graduate.profile.show') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-edit me-1"></i> My Profile
                </a>
                {{-- هذا الرابط يؤدي إلى GraduateRecommendationController@index الذي يعرض recommendations.index.blade.php --}}
                <a href="{{ route('graduate.recommendations.index') }}" class="btn btn-info btn-sm text-white">
                    <i class="fas fa-star me-1"></i> View Recommendations
                </a>
                 {{-- هذا الرابط يؤدي إلى JobOpportunityController@index الذي يعرض jobs.index.blade.php --}}
                 <a href="{{ route('jobs.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-search me-1"></i> Browse Jobs
                </a>
                {{-- هذا الرابط يؤدي إلى TrainingCourseController@index الذي يعرض courses.index.blade.php --}}
                <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-search me-1"></i> Browse Courses
                </a>
            </div>
        </div>
    </div>

    {{-- قسم طلبات الترقية --}}
    @if(Auth::user()->type === 'خريج')
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <i class="fas fa-level-up-alt me-2"></i> Account Upgrade Options
            </div>
            <div class="card-body">
                @if(isset($pendingUpgradeRequest) && $pendingUpgradeRequest)
                    {{-- ... (كما في الرد السابق) ... --}}
                     <div class="alert alert-info"> Your request to upgrade to <strong>{{ $pendingUpgradeRequest->requested_role }}</strong> is {{ $pendingUpgradeRequest->status }}. @if($pendingUpgradeRequest->status === 'rejected' && $pendingUpgradeRequest->admin_notes)<br>Admin Notes: {{ $pendingUpgradeRequest->admin_notes }}@endif </div> @if($pendingUpgradeRequest->status === 'rejected') <hr> <p class="mt-2 text-muted">You can submit a new request if you wish.</p> @include('partials._upgrade_request_buttons') @endif
                @else
                    <p class="text-muted mb-3">Request an upgrade to contribute more.</p>
                    {{-- هذا النموذج يشير إلى route('upgrade.request.store') --}}
                    @include('partials._upgrade_request_buttons')
                    <small class="d-block mt-3 text-muted">* Subject to admin approval.</small>
                @endif
            </div>
        </div>
    @endif

    {{-- قسم آخر طلبات التوظيف --}}
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <span><i class="fas fa-file-alt me-2 text-primary"></i> Recent Job Applications</span>
                    {{-- هذا الرابط يؤدي إلى GraduateJobApplicationController@index الذي يعرض applications.index.blade.php --}}
                    <a href="{{ route('graduate.applications.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2">View All</a>
                </div>
                 <div class="list-group list-group-flush">
                    @if(isset($recentApplications) && !$recentApplications->isEmpty())
                            @foreach ($recentApplications as $application)
                                {{-- هذا الرابط يؤدي إلى GraduateJobApplicationController@show الذي يعرض applications.show.blade.php --}}
                                <a href="{{ route('graduate.applications.show', $application) }}" class="list-group-item list-group-item-action px-3 py-2">
                                    {{-- ... (تفاصيل الطلب كما في الرد السابق) ... --}}
                                     <div class="d-flex w-100 justify-content-between"> <p class="mb-1 fw-bold">{{ $application->jobOpportunity?->{'Job Title'} ?? 'Job Not Found' }}</p> <small class="text-muted">{{ $application->Date ? $application->Date->diffForHumans() : 'N/A' }}</small> </div> <small class="text-muted d-block mb-1">Status: <span class="badge fs-7 {{ match ($application->Status ?? '') { 'Pending' => 'bg-secondary', 'Reviewed' => 'bg-info text-dark', 'Shortlisted' => 'bg-primary', 'Hired' => 'bg-success', 'Rejected' => 'bg-danger', default => 'bg-light text-dark', } }}">{{ $application->Status ?? 'N/A' }}</span> </small>
                                </a>
                            @endforeach
                    @else
                        {{-- ... (رسالة "لا يوجد طلبات" كما في الرد السابق) ... --}}
                         <div class="list-group-item p-4 text-center text-muted"> <p class="mb-2"><i class="fas fa-folder-open fa-2x mb-2 d-block"></i>You haven't applied for any jobs recently.</p> <a href="{{ route('jobs.index') }}" class="btn btn-success btn-sm"> <i class="fas fa-search me-1"></i> Find Opportunities </a> </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- قسم الدورات النشطة --}}
        <div class="col-lg-5">
             <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <span><i class="fas fa-book-reader me-2 text-success"></i> Active Courses</span>
                    {{-- هذا الرابط يؤدي إلى GraduateEnrollmentController@index الذي يعرض enrollments.index.blade.php --}}
                     <a href="{{ route('graduate.enrollments.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    @if(isset($activeEnrollments) && !$activeEnrollments->isEmpty())
                         @foreach ($activeEnrollments as $enrollment)
                            {{-- هذا الرابط يؤدي إلى GraduateEnrollmentController@show الذي يعرض enrollments.show.blade.php --}}
                            <a href="{{ route('graduate.enrollments.show', $enrollment) }}" class="list-group-item list-group-item-action px-3 py-2">
                                {{-- ... (تفاصيل التسجيل كما في الرد السابق) ... --}}
                                 <div class="d-flex w-100 justify-content-between"> <p class="mb-1 fw-bold">{{ $enrollment->trainingCourse?->{'Course name'} ?? 'Course Not Found' }}</p> <span class="badge {{ $enrollment->Status === 'قيد التقدم' ? 'bg-primary' : 'bg-secondary' }}">{{ $enrollment->Status ?? 'N/A' }}</span> </div> <small class="text-muted d-block">Enrolled: {{ $enrollment->Date ? $enrollment->Date->format('Y-m-d') : 'N/A' }}</small>
                             </a>
                        @endforeach
                    @else
                        {{-- ... (رسالة "لا يوجد دورات" كما في الرد السابق) ... --}}
                         <div class="list-group-item p-4 text-center text-muted"> <p class="mb-2"><i class="fas fa-book-open fa-2x mb-2 d-block"></i>You are not enrolled in any active courses.</p> <a href="{{ route('courses.index') }}" class="btn btn-success btn-sm"> <i class="fas fa-search me-1"></i> Browse Courses </a> </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection