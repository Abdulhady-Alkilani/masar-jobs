@extends('layouts.company')
@section('title', 'Application Details')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-file-invoice me-2"></i> Application: {{ $application->user->username ?? 'Applicant' }} for {{ Str::limit($application->jobOpportunity->{'Job Title'}, 30) }}
        </h2>
         <a href="{{ route('company-manager.job-applications.index', ['job_id' => $application->JobID]) }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to Applications for this Job
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        {{-- معلومات المتقدم --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <i class="fas fa-user-tie me-2"></i> Applicant Information
                </div>
                <div class="card-body">
                    @if($application->user)
                        <dl class="mb-0">
                            <dt>Name:</dt>
                            <dd>{{ $application->user->first_name }} {{ $application->user->last_name }}</dd>

                            <dt>Username:</dt>
                            <dd>{{ $application->user->username }}</dd>

                            <dt>Email:</dt>
                            <dd><a href="mailto:{{ $application->user->email }}">{{ $application->user->email }}</a></dd>

                            <dt>Phone:</dt>
                            <dd>{{ $application->user->phone ?? 'N/A' }}</dd>

                            {{-- عرض بعض بيانات البروفايل إن وجدت --}}
                            @if($application->user->profile)
                                <dt>University:</dt>
                                <dd>{{ $application->user->profile->University ?? 'N/A' }}</dd>
                                <dt>GPA:</dt>
                                <dd>{{ $application->user->profile->GPA ?? 'N/A' }}</dd>
                                <dt>Bio:</dt>
                                <dd class="text-muted small">{{ Str::limit($application->user->profile->{'Personal Description'}, 150) ?? 'N/A' }}</dd>
                            @endif
                        </dl>
                    @else
                        <p class="text-danger">Applicant data not found.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- معلومات الطلب والوظيفة --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <i class="fas fa-briefcase me-2"></i> Application & Job Details
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Job Title:</dt>
                        <dd class="col-sm-8"><a href="{{ route('jobs.show', $application->jobOpportunity) }}" target="_blank">{{ $application->jobOpportunity->{'Job Title'} }} <i class="fas fa-external-link-alt fa-xs"></i></a></dd>

                        <dt class="col-sm-4">Application ID:</dt>
                        <dd class="col-sm-8">{{ $application->ApplicationID }}</dd>

                        <dt class="col-sm-4">Applied On:</dt>
                        <dd class="col-sm-8">{{ $application->Date ? $application->Date->format('Y-m-d H:i') : 'N/A' }}</dd>

                        <dt class="col-sm-4">Current Status:</dt>
                        <dd class="col-sm-8">
                            <span class="badge fs-6 {{-- Add Bootstrap classes for status color --}}
                                @if($application->Status == 'Pending') bg-warning text-dark
                                @elseif($application->Status == 'Reviewed') bg-info text-dark
                                @elseif($application->Status == 'Shortlisted') bg-primary
                                @elseif($application->Status == 'Hired') bg-success
                                @elseif($application->Status == 'Rejected') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ $application->Status }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Applicant's Note:</dt>
                        <dd class="col-sm-8 text-muted">{{ $application->Description ?? 'No note provided.' }}</dd>

                         <dt class="col-sm-4 mt-3">CV / Resume:</dt>
                         <dd class="col-sm-8 mt-3">
                            @if($application->CV)
                                {{-- !!! هنا يجب إنشاء مسار آمن لتنزيل الـ CV !!! --}}
                                {{-- <a href="{{ route('company-manager.applications.download-cv', $application) }}" class="btn btn-sm btn-success"> --}}
                                <a href="#" class="btn btn-sm btn-success disabled" title="Download CV (Setup download route)">
                                    <i class="fas fa-download me-1"></i> Download CV
                                </a>
                                <small class="d-block text-muted">{{ basename($application->CV) }}</small>
                            @else
                                <span class="text-danger">No CV uploaded.</span>
                            @endif
                         </dd>
                    </dl>
                    <hr class="my-3">
                    {{-- نموذج تغيير حالة الطلب --}}
                    <form action="{{ route('company-manager.job-applications.update', $application) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label for="Status" class="form-label small">Change Application Status:</label>
                                <select name="Status" id="Status" class="form-select form-select-sm @error('Status') is-invalid @enderror" required>
                                    <option value="Pending" {{ $application->Status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Reviewed" {{ $application->Status == 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    <option value="Shortlisted" {{ $application->Status == 'Shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                    <option value="Hired" {{ $application->Status == 'Hired' ? 'selected' : '' }}>Hired</option>
                                    <option value="Rejected" {{ $application->Status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('Status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection