@extends('layouts.company') {{-- أو layouts.app إذا لم يكن هناك layout خاص بمدير الشركة --}}
@section('title', 'Job Applications Received')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-users-cog me-2"></i> Job Applications</h2>
        {{-- يمكنك إضافة رابط للعودة للداشبورد أو لإدارة الوظائف --}}
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="card-title mb-0 me-2">Received Applications</span>
            {{-- Filter Form --}}
            <form action="{{ route('company-manager.job-applications.index') }}" method="GET" class="d-inline-flex flex-grow-1 flex-wrap justify-content-end gap-2">
                <select name="job_id" class="form-select form-select-sm" style="max-width: 250px;">
                    <option value="">All My Job Postings</option>
                    @foreach($managerJobs as $job)
                        <option value="{{ $job->JobID }}" {{ request('job_id') == $job->JobID ? 'selected' : '' }}>
                            {{ Str::limit($job->{'Job Title'}, 40) }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Reviewed" {{ request('status') == 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="Shortlisted" {{ request('status') == 'Shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Hired" {{ request('status') == 'Hired' ? 'selected' : '' }}>Hired</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('company-manager.job-applications.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filter">Clear</a>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Applicant Name</th>
                            <th>Job Title</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr>
                                <td class="ps-3">
                                    {{ $application->user->username ?? 'N/A' }}
                                    @if($application->user)
                                        <small class="d-block text-muted">{{ $application->user->email }}</small>
                                    @endif
                                </td>
                                <td>{{ $application->jobOpportunity ? Str::limit($application->jobOpportunity->{'Job Title'}, 40) : 'N/A' }}</td>
                                <td>{{ $application->Date ? $application->Date->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    <span class="badge
                                        @if($application->Status == 'Pending') bg-warning text-dark
                                        @elseif($application->Status == 'Reviewed') bg-info text-dark
                                        @elseif($application->Status == 'Shortlisted') bg-primary
                                        @elseif($application->Status == 'Hired') bg-success
                                        @elseif($application->Status == 'Rejected') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ $application->Status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('company-manager.job-applications.show', $application) }}" class="btn btn-info btn-sm" title="View Application">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    {{-- لا يوجد عادة تعديل أو حذف مباشر للطلبات من قبل المدير، بل تغيير الحالة --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No applications found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($applications->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $applications->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection