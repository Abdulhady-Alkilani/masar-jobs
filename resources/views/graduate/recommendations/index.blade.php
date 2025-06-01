@extends('layouts.graduate') {{-- أو layouts.app --}}
@section('title', 'Recommendations For You')

@section('header')
    {{-- عنوان الصفحة --}}
    <h2 class="h4 mb-0 text-primary"><i class="fas fa-star me-2"></i> Recommendations For You</h2>
@endsection

@section('content')
    @include('partials._alerts')

    {{-- رسالة تعريفية --}}
    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
      <i class="fas fa-info-circle fa-lg me-3"></i>
      <div>
        Hi {{ $user->first_name }}! These recommendations are based on your profile information and skills. Keep your profile updated for better suggestions.
        <a href="{{ route('graduate.profile.edit') }}" class="alert-link fw-bold">Update Profile Now</a>.
      </div>
    </div>

    <div class="row g-4">
        {{-- ====================================== --}}
        {{-- قسم توصيات الوظائف                --}}
        {{-- ====================================== --}}
        <div class="col-lg-6 d-flex flex-column"> {{-- d-flex flex-column لجعل الكارد يأخذ الارتفاع المتاح --}}
            <div class="card shadow-sm flex-grow-1"> {{-- flex-grow-1 لجعل الكارد يملأ المساحة --}}
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2 text-info"></i> Recommended Job Opportunities</h5>
                </div>
                {{-- استخدام list-group لعرض الوظائف --}}
                <div class="list-group list-group-flush">
                    @forelse ($recommendedJobs as $job)
                        <a href="{{ route('jobs.show', $job) }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex w-100 justify-content-between mb-1">
                                <h6 class="mb-0 fw-bold text-primary">{{ $job->{'Job Title'} }}</h6>
                                <small class="text-muted">{{ $job->Date ? $job->Date->diffForHumans() : '' }}</small>
                            </div>
                             <p class="mb-1 small text-muted">
                                {{-- عرض اسم الشركة --}}
                                @if($job->company)
                                    <i class="fas fa-building fa-fw me-1"></i>{{ $job->company->Name }}
                                @elseif($job->user?->company)
                                     <i class="fas fa-building fa-fw me-1"></i>{{ $job->user->company->Name }}
                                @endif
                                {{-- عرض الموقع --}}
                                @if($job->Site) <span class="ms-2"><i class="fas fa-map-marker-alt fa-fw me-1"></i>{{ $job->Site }}</span> @endif
                                {{-- عرض النوع --}}
                                @if($job->Type) <span class="ms-2 badge bg-secondary">{{ $job->Type }}</span> @endif
                             </p>
                             {{-- عرض بعض المهارات إن وجدت --}}
                             @if($job->Skills)
                                <p class="mb-0 small">
                                    <strong class="text-dark">Skills:</strong> <span class="text-muted">{{ Str::limit($job->Skills, 100) }}</span>
                                </p>
                             @endif
                        </a>
                    @empty
                        <div class="list-group-item text-muted text-center py-4">
                            <p>No specific job recommendations found based on your current profile.</p>
                            <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-primary mt-2">Browse All Jobs</a>
                            <p class="mt-2 mb-0 small"><a href="{{ route('graduate.profile.edit') }}" class="link-secondary">Update profile/skills?</a></p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ====================================== --}}
        {{-- قسم توصيات الدورات                  --}}
        {{-- ====================================== --}}
        <div class="col-lg-6 d-flex flex-column">
            <div class="card shadow-sm flex-grow-1">
                 <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2 text-success"></i> Recommended Training Courses</h5>
                </div>
                <div class="list-group list-group-flush">
                     @forelse ($recommendedCourses as $course)
                        <a href="{{ route('courses.show', $course) }}" class="list-group-item list-group-item-action py-3">
                             <div class="d-flex w-100 justify-content-between mb-1">
                                <h6 class="mb-0 fw-bold text-primary">{{ $course->{'Course name'} }}</h6>
                                {{-- عرض مستوى الدورة --}}
                                @if($course->Stage)<span class="badge bg-info text-dark ms-2">{{ $course->Stage }}</span>@endif
                            </div>
                             <p class="mb-1 small text-muted">
                                {{-- عرض المدرب --}}
                                @if($course->{'Trainers name'})<i class="fas fa-user-tie fa-fw me-1"></i>{{ $course->{'Trainers name'} }} @endif
                                {{-- عرض المنصة/الموقع --}}
                                @if($course->Site) <span class="ms-2"><i class="fas fa-map-marker-alt fa-fw me-1"></i>{{ $course->Site }}</span> @endif
                             </p>
                              {{-- عرض الشهادة --}}
                            <p class="mb-0 small">
                                <strong class="text-dark">Certificate:</strong>
                                <span class="text-{{ $course->Certificate == 'يوجد' ? 'success' : 'danger' }}">
                                    {{ $course->Certificate == 'يوجد' ? 'Available' : 'Not Available' }}
                                </span>
                            </p>
                        </a>
                     @empty
                         <div class="list-group-item text-muted text-center py-4">
                            <p>No specific course recommendations at this moment.</p>
                            <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-primary mt-2">Browse All Courses</a>
                         </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ====================================== --}}
        {{-- قسم توصيات الذكاء الاصطناعي (اختياري) --}}
        {{-- ====================================== --}}
        @if (!empty($aiRecommendations))
             <div class="col-12">
                <div class="card shadow-sm border-warning"> {{-- تمييز البطاقة بلون --}}
                    <div class="card-header bg-warning text-dark"> {{-- استخدام لون تحذيري --}}
                        <h5 class="mb-0"><i class="fas fa-robot me-2"></i> AI-Powered Suggestions</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Based on your profile and activity, you might also be interested in:</p>
                        <ul class="list-unstyled"> {{-- قائمة غير منقطة --}}
                             @foreach($aiRecommendations as $rec)
                                <li class="mb-1"><i class="fas fa-lightbulb fa-fw text-warning me-1"></i> {{-- أيقونة --}} {{-- اعرض التوصية $rec --}}</li>
                             @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

    </div> {{-- نهاية row --}}
@endsection