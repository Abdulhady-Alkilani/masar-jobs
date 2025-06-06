@extends('layouts.admin') {{-- أو layouts.app --}}

@section('title', 'Admin Dashboard')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-tachometer-alt me-2"></i> {{ __('general.dashboard') }} {{-- استخدام الترجمة --}}
        </h2>
        {{-- <a href="#" class="btn btn-sm btn-outline-success"><i class="fas fa-plus me-1"></i> Quick Action</a> --}}
    </div>
@endsection

@section('content')
<div class="container-fluid">
    {{-- !!! تأكد أن هذا السطر مكتوب بشكل صحيح هكذا: !!! --}}
    @include('partials._alerts')

    {{-- قسم الإحصائيات --}}
    <div class="mb-4">
        <h3 class="mb-3 border-bottom pb-2 text-muted"><small>@lang('general.statistics_overview')</small></h3>
        <div class="row g-3"> {{-- استخدام g-3 لمسافات أصغر --}}
            {{-- Stat: Total Users --}}
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card text-center h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                         <div class="mb-2"><i class="fas fa-users fa-3x text-primary"></i></div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">@lang('general.total_users')</h6>
                        <p class="card-text h3 fw-bold text-primary mb-0">{{ $stats['total_users'] ?? 'N/A' }}</p>
                        <a href="{{ route('admin.users.index') }}" class="stretched-link" aria-label="@lang('general.manage_users')"></a>
                    </div>
                </div>
            </div>
            {{-- Stat: Total Companies --}}
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card text-center h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                        <div class="mb-2"><i class="fas fa-building fa-3x text-success"></i></div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">@lang('general.total_companies')</h6>
                        <p class="card-text h3 fw-bold text-success mb-0">{{ $stats['total_companies'] ?? 'N/A' }}</p>
                         <a href="{{ route('admin.companies.index') }}" class="stretched-link" aria-label="@lang('general.manage_companies')"></a>
                    </div>
                </div>
            </div>
            {{-- Stat: Active Jobs --}}
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                 <div class="card text-center h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                        <div class="mb-2"><i class="fas fa-briefcase fa-3x text-info"></i></div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">@lang('general.active_jobs')</h6>
                        <p class="card-text h3 fw-bold text-info mb-0">{{ $stats['active_jobs'] ?? 'N/A' }}</p>
                        <a href="{{ route('admin.job-opportunities.index') }}" class="stretched-link" aria-label="@lang('general.manage_jobs')"></a>
                    </div>
                </div>
            </div>
            {{-- Stat: Pending Company Requests --}}
             <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                 <div class="card text-center h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                         <div class="mb-2"><i class="fas fa-file-signature fa-3x text-warning"></i></div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">@lang('general.company_requests')</h6>
                        <p class="card-text h3 fw-bold text-warning mb-0">{{ $stats['pending_companies'] ?? '0' }}</p>
                        <a href="{{ route('admin.company-requests.index') }}" class="stretched-link" aria-label="@lang('general.company_requests')"></a>
                    </div>
                </div>
            </div>
            {{-- Stat: Upgrade Requests --}}
            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card text-center h-100 shadow-sm border-0">
                   <div class="card-body d-flex flex-column justify-content-center py-4">
                       <div class="mb-2"><i class="fas fa-user-shield fa-3x text-danger"></i></div>
                       <h6 class="card-title text-muted small text-uppercase mb-1">@lang('general.upgrade_requests')</h6>
                       <p class="card-text h3 fw-bold text-danger mb-0">{{ $stats['pending_upgrade_requests'] ?? '0' }}</p>
                       <a href="{{ route('admin.upgrade-requests.index') }}" class="stretched-link" aria-label="@lang('general.upgrade_requests')"></a>
                   </div>
               </div>
           </div>
        </div>
    </div>

    {{-- قسم الأنشطة الأخيرة --}}
    <div class="mt-4">
        <h3 class="mb-3 border-bottom pb-2 text-muted"><small>@lang('general.recent_activity')</small></h3>
        <div class="row g-4">
             <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white"><i class="fas fa-users-cog me-2"></i> @lang('general.recently_registered_users')</div>
                    <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                        @forelse($recentUsers ?? [] as $user)
                            <a href="{{ route('admin.users.show', $user) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <span class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</span>
                                    <small class="d-block text-muted">{{ $user->email }} ({{ $user->type }})</small>
                                </div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </a>
                        @empty
                            <div class="list-group-item text-muted text-center">@lang('general.no_data_found')</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white"><i class="fas fa-user-shield me-2"></i> @lang('general.recent_upgrade_requests') (Pending)</div>
                     <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                        @forelse($recentUpgradeRequests ?? [] as $request) {{-- ستحتاج لتمرير هذا المتغير من الكنترولر --}}
                                <a href="{{ route('admin.upgrade-requests.show', $request) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                                    <div>
                                        <span class="fw-semibold">{{ $request->user->username ?? 'N/A' }}</span>
                                        <small class="d-block text-muted">requests to be: {{ $request->requested_role }}</small>
                                    </div>
                                    <small class="badge bg-warning text-dark">{{ $request->status }}</small>
                                </a>
                        @empty
                            <div class="list-group-item text-muted text-center">@lang('general.no_data_found')</div>
                        @endforelse
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>
@endsection