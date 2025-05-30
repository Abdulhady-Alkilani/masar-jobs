@extends('layouts.admin')
@section('title', 'Opportunity Details: ' . $jobOpportunity->{'Job Title'})

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-briefcase me-2"></i> Opportunity Details: {{ Str::limit($jobOpportunity->{'Job Title'}, 40) }}
        </h2>
         <a href="{{ route('admin.job-opportunities.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-info-circle me-2"></i>Opportunity Information</span>
            <span class="badge {{ $jobOpportunity->Status === 'مفعل' ? 'bg-success' : ($jobOpportunity->Status === 'معلق' ? 'bg-warning text-dark' : 'bg-danger') }}">
                {{ $jobOpportunity->Status }}
            </span>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Job Title</dt>
                <dd class="col-sm-9">{{ $jobOpportunity->{'Job Title'} }}</dd>

                <dt class="col-sm-3">Company (Manager)</dt>
                <dd class="col-sm-9">
                    @if($jobOpportunity->user)
                        {{ $jobOpportunity->user->company->Name ?? $jobOpportunity->user->username }}
                        (<a href="{{ route('admin.users.show', $jobOpportunity->user) }}">View Manager</a>)
                         @if($jobOpportunity->user->company)
                         | (<a href="{{ route('admin.companies.show', $jobOpportunity->user->company) }}">View Company</a>)
                         @endif
                    @else
                        N/A
                    @endif
                </dd>

                 <dt class="col-sm-3">Type</dt>
                 <dd class="col-sm-9"><span class="badge bg-info">{{ $jobOpportunity->Type }}</span></dd>

                 <dt class="col-sm-3">Location / Site</dt>
                 <dd class="col-sm-9">{{ $jobOpportunity->Site }}</dd>

                 <dt class="col-sm-3">Application End Date</dt>
                 <dd class="col-sm-9">{{ $jobOpportunity->{'End Date'} ? \Carbon\Carbon::parse($jobOpportunity->{'End Date'})->format('Y-m-d') : 'N/A' }}</dd>

                 <dt class="col-sm-3">Posted Date</dt>
                 <dd class="col-sm-9">{{ $jobOpportunity->Date ? \Carbon\Carbon::parse($jobOpportunity->Date)->format('Y-m-d') : 'N/A' }}</dd>

                <dt class="col-sm-3">Qualifications</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $jobOpportunity->Qualification ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Required Skills</dt>
                <dd class="col-sm-9">{{ $jobOpportunity->Skills ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Job Description</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $jobOpportunity->{'Job Description'} }}</dd>

            </dl>
        </div>
    </div>

     {{-- قسم عرض المتقدمين (اختياري، يمكن أن يكون في صفحة منفصلة) --}}
     <div class="card shadow-sm mb-4">
        <div class="card-header">
             <i class="fas fa-users me-2"></i> Job Applications ({{ $jobOpportunity->jobApplications->count() }})
        </div>
         <div class="card-body p-0">
             <div class="table-responsive">
                 <table class="table table-hover align-middle mb-0">
                     <thead class="table-light">
                         <tr>
                             <th class="ps-3">Applicant</th>
                             <th>Email</th>
                             <th>Applied At</th>
                             <th>Status</th>
                             <th class="text-end pe-3">Actions</th>
                         </tr>
                     </thead>
                     <tbody>
                        @forelse($jobOpportunity->jobApplications as $application)
                        <tr>
                            <td class="ps-3">
                                 <a href="{{ route('admin.users.show', $application->user) }}">{{ $application->user->username ?? 'N/A' }}</a>
                            </td>
                             <td>{{ $application->user->email ?? 'N/A' }}</td>
                             <td>{{ $application->Date ? $application->Date->format('Y-m-d') : 'N/A' }}</td>
                             <td>
                                 <span class="badge {{ $application->Status === 'Pending' ? 'bg-secondary' : ($application->Status === 'Reviewed' ? 'bg-info' : ($application->Status === 'Shortlisted' ? 'bg-primary' : ($application->Status === 'Hired' ? 'bg-success' : 'bg-danger'))) }}">
                                     {{ $application->Status }}
                                 </span>
                             </td>
                             <td class="text-end pe-3">
                                {{-- رابط لعرض تفاصيل الطلب (إذا كان لديك صفحة لذلك) --}}
                                {{-- <a href="#" class="btn btn-sm btn-outline-info">View App</a> --}}
                             </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No applications yet.</td></tr>
                        @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
    </div>

    {{-- أزرار الإجراءات الرئيسية --}}
    <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="{{ route('admin.job-opportunities.edit', $jobOpportunity) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Opportunity
        </a>
        <form action="{{ route('admin.job-opportunities.destroy', $jobOpportunity) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Delete Opportunity
            </button>
        </form>
         <a href="{{ route('admin.job-opportunities.index') }}" class="btn btn-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection