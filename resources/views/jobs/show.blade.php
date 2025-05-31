@extends('layouts.app')
@section('title', $jobOpportunity->{'Job Title'})

{{-- @section('header')
    <h2 class="h4 mb-0 text-primary"><i class="fas fa-briefcase me-2"></i> Job Details</h2>
@endsection --}}

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light">
             <div class="d-flex justify-content-between align-items-center flex-wrap">
                 <h4 class="mb-0 me-3">{{ $jobOpportunity->{'Job Title'} }}</h4>
                 <div class="text-muted small">
                     @if($jobOpportunity->company)
                         <i class="fas fa-building me-1"></i> <a href="{{ route('companies.show', $jobOpportunity->company) }}" class="link-secondary">{{ $jobOpportunity->company->Name }}</a>
                     @elseif($jobOpportunity->user?->company)
                          <i class="fas fa-building me-1"></i> <a href="{{ route('companies.show', $jobOpportunity->user->company) }}" class="link-secondary">{{ $jobOpportunity->user->company->Name }}</a>
                     @endif
                      @if($jobOpportunity->Site)<span class="ms-3"><i class="fas fa-map-marker-alt me-1"></i> {{ $jobOpportunity->Site }}</span>@endif
                 </div>
             </div>
        </div>

        <div class="card-body">
             @include('partials._alerts')

             {{-- تفاصيل الوظيفة --}}
             <div class="mb-4 pb-3 border-bottom">
                 <h5 class="mb-2">Job Description</h5>
                 <p class="text-muted" style="white-space: pre-wrap;">{{ $jobOpportunity->{'Job Description'} }}</p>
             </div>

             @if($jobOpportunity->Qualification)
                 <div class="mb-4 pb-3 border-bottom">
                     <h5 class="mb-2">Qualifications</h5>
                     <p class="text-muted" style="white-space: pre-wrap;">{{ $jobOpportunity->Qualification }}</p>
                 </div>
             @endif

             @if($jobOpportunity->Skills)
                 <div class="mb-4 pb-3 border-bottom">
                     <h5 class="mb-2">Required Skills</h5>
                      {{-- يمكنك عرض المهارات كقائمة أو badges --}}
                     <p class="text-muted">{{ $jobOpportunity->Skills }}</p>
                     {{-- مثال للـ Badges إذا كانت مفصولة بفاصلة --}}
                     {{-- <div>
                         @foreach(explode(',', $jobOpportunity->Skills) as $skill)
                            @if(trim($skill)) <span class="badge bg-secondary me-1">{{ trim($skill) }}</span> @endif
                         @endforeach
                     </div> --}}
                 </div>
             @endif

             <div class="row mb-4">
                 <div class="col-md-4">
                     <strong>Type:</strong> <span class="badge bg-info text-dark">{{ $jobOpportunity->Type }}</span>
                 </div>
                 <div class="col-md-4">
                    <strong>Posted On:</strong> {{ $jobOpportunity->Date ? $jobOpportunity->Date->format('Y-m-d') : 'N/A' }}
                 </div>
                  @if($jobOpportunity->{'End Date'})
                     <div class="col-md-4 text-danger">
                         <strong>Apply Before:</strong> {{ $jobOpportunity->{'End Date'}->format('Y-m-d') }}
                     </div>
                 @endif
             </div>

             {{-- زر/نموذج التقديم (يظهر فقط للخريج المسجل الذي لم يقدم بعد) --}}
             @auth
                 @if(Auth::user()->type === 'خريج')
                     @php
                         // التحقق مما إذا كان المستخدم قد قدم على هذه الوظيفة بالفعل
                         $hasApplied = Auth::user()->jobApplications()->where('JobID', $jobOpportunity->JobID)->exists();
                     @endphp

                     @if($hasApplied)
                          <div class="alert alert-success text-center" role="alert">
                              <i class="fas fa-check-circle me-1"></i> You have already applied for this opportunity.
                              <a href="{{ route('graduate.applications.index') }}" class="alert-link">View Applications</a>
                          </div>
                     @elseif($jobOpportunity->Status !== 'مفعل' || ($jobOpportunity->{'End Date'} && $jobOpportunity->{'End Date'} < now()))
                         <div class="alert alert-warning text-center" role="alert">
                             <i class="fas fa-exclamation-triangle me-1"></i> Applications for this opportunity are now closed.
                         </div>
                     @else
                         {{-- نموذج التقديم --}}
                         <div class="card mt-4 bg-light border">
                             <div class="card-body">
                                 <h5 class="card-title mb-3">Apply Now</h5>
                                 <form action="{{ route('jobs.apply', $jobOpportunity) }}" method="POST" enctype="multipart/form-data">
                                     @csrf
                                     <div class="mb-3">
                                         <label for="CV" class="form-label">Upload CV (PDF, DOC, DOCX - Max 5MB) <span class="text-danger">*</span></label>
                                         <input class="form-control @error('CV') is-invalid @enderror" type="file" id="CV" name="CV" required accept=".pdf,.doc,.docx">
                                          @error('CV') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                     </div>
                                     <div class="mb-3">
                                         <label for="Description" class="form-label">Cover Letter / Notes (Optional)</label>
                                         <textarea class="form-control @error('Description') is-invalid @enderror" id="Description" name="Description" rows="4">{{ old('Description') }}</textarea>
                                          @error('Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                     </div>
                                     <button type="submit" class="btn btn-success">
                                         <i class="fas fa-paper-plane me-1"></i> Submit Application
                                     </button>
                                 </form>
                             </div>
                         </div>
                     @endif
                 @endif
             @endauth

             {{-- زر العودة للقائمة --}}
             <div class="text-center mt-4">
                 <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                     <i class="fas fa-arrow-left me-1"></i> Back to Job List
                 </a>
             </div>

        </div> {{-- نهاية card-body --}}
    </div> {{-- نهاية card --}}
@endsection