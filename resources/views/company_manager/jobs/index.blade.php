@extends('layouts.company')
@section('title', 'My Job Opportunities')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-briefcase me-2"></i> My Job Opportunities</h2>
        <a href="{{ route('company-manager.job-opportunities.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Post New Opportunity
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="card-title mb-0 me-2">Your Posted Opportunities</span>
             {{-- Filter Form --}}
             <form action="{{ route('company-manager.job-opportunities.index') }}" method="GET" class="d-inline-flex flex-grow-1 flex-wrap justify-content-end gap-2">
                <input type="text" name="search" class="form-control form-control-sm" style="max-width: 200px;" placeholder="Search title..." value="{{ request('search') }}">
                <select name="type" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="">All Types</option>
                    <option value="وظيفة" {{ request('type') == 'وظيفة' ? 'selected' : '' }}>Job</option>
                    <option value="تدريب" {{ request('type') == 'تدريب' ? 'selected' : '' }}>Training</option>
                </select>
                 <select name="status" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="">All Statuses</option>
                    <option value="مفعل" {{ request('status') == 'مفعل' ? 'selected' : '' }}>Active</option>
                    <option value="معلق" {{ request('status') == 'معلق' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('company-manager.job-opportunities.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filter">Clear</a>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Job Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Posted Date</th>
                            <th>End Date</th>
                            <th class="text-center">Applicants</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobOpportunities as $job)
                            <tr>
                                <td class="ps-3">{{ Str::limit($job->{'Job Title'}, 50) }}</td>
                                <td><span class="badge {{ $job->Type === 'وظيفة' ? 'bg-success' : 'bg-info text-dark' }}">{{ $job->Type }}</span></td>
                                <td>
                                    <span class="badge {{ $job->Status === 'مفعل' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $job->Status }}
                                    </span>
                                </td>
                                <td>{{ $job->Date ? $job->Date->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $job->{'End Date'} ? $job->{'End Date'}->format('Y-m-d') : 'N/A' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('company-manager.job-applications.index', ['job_id' => $job->JobID]) }}" class="badge bg-primary rounded-pill text-decoration-none">
                                        {{ $job->jobApplications_count ?? $job->jobApplications()->count() }} {{-- عرض عدد المتقدمين --}}
                                    </a>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('company-manager.job-opportunities.show', $job) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('company-manager.job-opportunities.edit', $job) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('company-manager.job-opportunities.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will delete all applications for this job too.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    You haven't posted any job opportunities yet.
                                     <a href="{{ route('company-manager.job-opportunities.create') }}" class="btn btn-success btn-sm mt-2">Post Your First Opportunity</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($jobOpportunities->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $jobOpportunities->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection