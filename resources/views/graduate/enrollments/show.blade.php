@extends('layouts.graduate') {{-- أو layouts.app --}}

{{-- تحديد العنوان بناءً على الدورة --}}
@section('title', 'Enrollment: ' . ($enrollment->trainingCourse?->{'Course name'} ?? 'N/A'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-info-circle me-2"></i> Enrollment Details</h2>
        <a href="{{ route('graduate.enrollments.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to My Enrollments
        </a>
    </div>
@endsection

@section('content')
    @include('partials._alerts')

    <div class="row g-4">
        {{-- العمود الأيسر: تفاصيل الدورة --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-graduation-cap me-2"></i> Course Information</span>
                     @if($enrollment->trainingCourse)
                     <a href="{{ route('courses.show', $enrollment->trainingCourse) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Original Course Page">
                         <i class="fas fa-external-link-alt"></i> View Course
                     </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($enrollment->trainingCourse)
                        <h5 class="card-title mb-1">{{ $enrollment->trainingCourse->{'Course name'} }}</h5>
                         <p class="card-subtitle mb-2 text-muted">
                            {{ $enrollment->trainingCourse->{'Trainers name'} ?? $enrollment->trainingCourse->creator->username ?? 'N/A' }}
                             - {{ $enrollment->trainingCourse->Site ?? 'N/A' }}
                        </p>
                        <hr>
                        <p class="card-text small mt-3" style="white-space: pre-wrap;">
                            <strong>Description Snippet:</strong><br>
                            {{ Str::limit($enrollment->trainingCourse->{'Course Description'}, 300) }}
                         </p>
                         <p class="card-text small mt-2">
                            <strong>Stage:</strong> <span class="badge bg-secondary">{{ $enrollment->trainingCourse->Stage }}</span>
                            <strong class="ms-3">Certificate:</strong>
                            <span class="badge {{ $enrollment->trainingCourse->Certificate == 'يوجد' ? 'bg-success' : 'bg-light text-dark' }}">
                                {{ $enrollment->trainingCourse->Certificate == 'يوجد' ? 'Yes' : 'No' }}
                            </span>
                        </p>

                    @else
                        <div class="alert alert-warning" role="alert">
                            The original course associated with this enrollment could not be found. It might have been deleted.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- العمود الأيمن: تفاصيل تسجيلك --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                     <i class="fas fa-clipboard-check me-2"></i> Your Enrollment Status
                </div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>Enrollment ID:</dt>
                        <dd>{{ $enrollment->EnrollmentID }}</dd>

                        <dt>Enrolled On:</dt>
                        <dd>{{ $enrollment->Date ? $enrollment->Date->format('Y-m-d H:i') : 'N/A' }}</dd>

                        <dt>Current Status:</dt>
                        <dd>
                            <span class="badge fs-6 {{-- استخدام fs-6 لحجم أكبر --}}
                                {{ match ($enrollment->Status) {
                                    'قيد التقدم' => 'bg-primary',
                                    'مكتمل' => 'bg-success',
                                    'ملغي' => 'bg-danger',
                                    default => 'bg-secondary',
                                } }}">{{ $enrollment->Status ?? 'N/A' }}</span>
                        </dd>

                         <dt>Completion Date:</dt>
                        <dd>{{ $enrollment->{'Complet Date'} ? \Carbon\Carbon::parse($enrollment->{'Complet Date'})->format('Y-m-d') : 'Not Completed Yet' }}</dd>
                    </dl>

                    {{-- زر إلغاء التسجيل (إذا كانت الحالة تسمح) --}}
                    @if($enrollment->Status !== 'مكتمل' && $enrollment->Status !== 'ملغي')
                        <hr class="my-3">
                        <form action="{{ route('graduate.enrollments.destroy', $enrollment) }}" method="POST" class="text-center" onsubmit="return confirm('Are you sure you want to cancel this enrollment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times-circle me-1"></i> Cancel Enrollment
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection