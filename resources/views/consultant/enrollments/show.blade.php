@extends('layouts.consultant')
@section('title', 'Enrollment Details')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-user-graduate me-2"></i> Enrollment Details
        </h2>
         <a href="{{ route('consultant.course-enrollments.index', ['course_id' => $enrollment->CourseID]) }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to Enrollments for this Course
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        {{-- معلومات التسجيل والدورة --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Enrollment & Course Info
                </div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>Course Name:</dt>
                        <dd>{{ $enrollment->trainingCourse?->{'Course name'} ?? 'N/A' }}</dd>

                        <dt>Enrollment ID:</dt>
                        <dd>{{ $enrollment->EnrollmentID }}</dd>

                        <dt>Enrollment Date:</dt>
                        <dd>{{ $enrollment->Date ? $enrollment->Date->format('Y-m-d H:i') : 'N/A' }}</dd>

                        <dt>Current Status:</dt>
                        <dd>
                             <span class="badge {{ $enrollment->Status === 'مكتمل' ? 'bg-success' : ($enrollment->Status === 'قيد التقدم' ? 'bg-primary' : 'bg-secondary') }}">
                                {{ $enrollment->Status ?? 'N/A' }}
                            </span>
                        </dd>

                        <dt>Completion Date:</dt>
                        <dd>{{ $enrollment->{'Complet Date'} ? \Carbon\Carbon::parse($enrollment->{'Complet Date'})->format('Y-m-d') : 'Not Completed' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- معلومات الطالب --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                 <div class="card-header">
                    <i class="fas fa-user me-2"></i> Student Information
                </div>
                 <div class="card-body">
                     @if($enrollment->user)
                        <dl class="mb-0">
                            <dt>Username:</dt>
                            <dd><a href="{{ route('admin.users.show', $enrollment->user) }}" target="_blank">{{ $enrollment->user->username }} <i class="fas fa-external-link-alt fa-xs"></i></a></dd> {{-- رابط لملف المستخدم في الأدمن (إذا كان مناسباً) --}}

                            <dt>Name:</dt>
                            <dd>{{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}</dd>

                            <dt>Email:</dt>
                            <dd>{{ $enrollment->user->email }}</dd>

                             <dt>Phone:</dt>
                            <dd>{{ $enrollment->user->phone ?? 'N/A' }}</dd>

                            {{-- عرض بعض بيانات البروفايل إن وجدت --}}
                            @if($enrollment->user->profile)
                                <dt>University:</dt>
                                <dd>{{ $enrollment->user->profile->University ?? 'N/A' }}</dd>
                                 <dt>GPA:</dt>
                                <dd>{{ $enrollment->user->profile->GPA ?? 'N/A' }}</dd>
                            @endif

                        </dl>
                     @else
                        <p class="text-danger">Student data not found.</p>
                     @endif
                </div>
            </div>
        </div>
    </div>

     {{-- زر العودة في الأسفل --}}
     <div class="text-center mt-4">
        <a href="{{ route('consultant.course-enrollments.index') }}" class="btn btn-secondary">
             <i class="fas fa-list me-1"></i> View All Enrollments
        </a>
    </div>
@endsection