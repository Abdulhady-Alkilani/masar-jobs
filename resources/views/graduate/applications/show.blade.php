@extends('layouts.graduate') {{-- أو layouts.app --}}

{{-- تحديد العنوان بناءً على الوظيفة --}}
@section('title', 'Application for: ' . ($application->jobOpportunity?->{'Job Title'} ?? 'N/A'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-file-alt me-2"></i> Application Details</h2>
        <a href="{{ route('graduate.applications.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to My Applications
        </a>
    </div>
@endsection

@section('content')
    @include('partials._alerts')

    <div class="row g-4">
        {{-- العمود الأيسر: تفاصيل الوظيفة --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-briefcase me-2"></i> Job Opportunity Details</span>
                     {{-- زر لعرض صفحة الوظيفة العامة --}}
                     @if($application->jobOpportunity)
                     <a href="{{ route('jobs.show', $application->jobOpportunity) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Original Job Posting">
                         <i class="fas fa-external-link-alt"></i> View Posting
                     </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($application->jobOpportunity)
                        <h5 class="card-title mb-1">{{ $application->jobOpportunity->{'Job Title'} }}</h5>
                        <p class="card-subtitle mb-2 text-muted">
                             {{-- عرض اسم الشركة --}}
                             @if($application->jobOpportunity->company)
                                {{ $application->jobOpportunity->company->Name }}
                             @elseif($application->jobOpportunity->user?->company)
                                 {{ $application->jobOpportunity->user->company->Name }}
                             @endif
                             - {{ $application->jobOpportunity->Site ?? 'N/A' }}
                        </p>
                        <hr>
                        <p class="card-text small mt-3" style="white-space: pre-wrap;">
                            <strong>Description Snippet:</strong><br>
                            {{ Str::limit($application->jobOpportunity->{'Job Description'}, 300) }}
                         </p>
                         <p class="card-text small mt-2">
                            <strong>Type:</strong> <span class="badge bg-secondary">{{ $application->jobOpportunity->Type }}</span>
                        </p>
                         <p class="card-text small mt-2">
                            <strong>Status (Job):</strong> <span class="badge {{ $application->jobOpportunity->Status === 'مفعل' ? 'bg-success' : 'bg-danger' }}">{{ $application->jobOpportunity->Status }}</span>
                        </p>
                    @else
                        <div class="alert alert-warning" role="alert">
                            The original job opportunity associated with this application could not be found. It might have been deleted.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- العمود الأيمن: تفاصيل طلبك --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                     <i class="fas fa-paper-plane me-2"></i> Your Application
                </div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>Application ID:</dt>
                        <dd>{{ $application->ApplicationID }}</dd>

                        <dt>Applied On:</dt>
                        <dd>{{ $application->Date ? $application->Date->format('Y-m-d H:i') : 'N/A' }}</dd>

                        <dt>Current Status:</dt>
                        <dd>
                            <span class="badge fs-6 {{-- استخدام fs-6 لحجم أكبر --}}
                                {{ match ($application->Status) {
                                    'Pending' => 'bg-secondary',
                                    'Reviewed' => 'bg-info text-dark',
                                    'Shortlisted' => 'bg-primary',
                                    'Hired' => 'bg-success',
                                    'Rejected' => 'bg-danger',
                                    default => 'bg-light text-dark',
                                } }}">{{ $application->Status ?? 'N/A' }}</span>
                        </dd>

                        <dt>Your Cover Letter/Notes:</dt>
                        <dd class="text-muted fst-italic bg-light p-2 rounded border" style="white-space: pre-wrap;">{{ $application->Description ?? 'No notes provided.' }}</dd>

                        <dt>Submitted CV:</dt>
                        <dd>
                            @if($application->CV)
                                {{-- !!! هام: تحتاج لطريقة آمنة لتنزيل الـ CV !!! --}}
                                {{-- هذا مجرد مثال لعرض اسم الملف، قد تحتاج لمسار تنزيل مخصص --}}
                                {{-- <a href="{{ route('graduate.applications.downloadCv', $application) }}" class="btn btn-sm btn-outline-success"> --}}
                                <a href="#" class="btn btn-sm btn-outline-success disabled" aria-disabled="true"> {{-- زر معطل مؤقتًا --}}
                                    <i class="fas fa-download me-1"></i> Download CV ({{ basename($application->CV) }})
                                </a>
                                <small class="text-danger d-block mt-1">Download functionality needs implementation.</small>
                            @else
                                <span class="text-danger">CV not found or not submitted.</span>
                            @endif
                        </dd>
                    </dl>

                     {{-- زر سحب الطلب (إذا كانت الحالة تسمح) --}}
                     @if(in_array($application->Status, ['Pending', 'Reviewed'])) {{-- مثال للحالات المسموح بها --}}
                        <hr class="my-3">
                        <form action="{{ route('graduate.applications.destroy', $application) }}" method="POST" class="text-center" onsubmit="return confirm('Are you sure you want to withdraw this application?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times-circle me-1"></i> Withdraw Application
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection