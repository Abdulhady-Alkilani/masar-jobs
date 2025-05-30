@extends('layouts.company')
@section('title', 'Enrollment Details')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-user-graduate me-2"></i> Enrollment: {{ $enrollment->user->username ?? 'Student' }} - {{ Str::limit($enrollment->trainingCourse->{'Course name'}, 30) }}
        </h2>
         <a href="{{ route('company-manager.course-enrollments.index', ['course_id' => $enrollment->CourseID]) }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to Enrollments
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        {{-- معلومات الطالب --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i> Student Information
                </div>
                <div class="card-body">
                    @if($enrollment->user)
                        <dl class="mb-0">
                            <dt>Username:</dt>
                            <dd>{{ $enrollment->user->username }}</dd>
                            <dt>Name:</dt>
                            <dd>{{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}</dd>
                            <dt>Email:</dt>
                            <dd><a href="mailto:{{ $enrollment->user->email }}">{{ $enrollment->user->email }}</a></dd>
                            <dt>Phone:</dt>
                            <dd>{{ $enrollment->user->phone ?? 'N/A' }}</dd>
                            @if($enrollment->user->profile)
                                <dt>University:</dt>
                                <dd>{{ $enrollment->user->profile->University ?? 'N/A' }}</dd>
                            @endif
                        </dl>
                    @else
                        <p class="text-danger">Student data not found.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- معلومات التسجيل والدورة وإجراءات المدير --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Enrollment & Course Details
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Course Name:</dt>
                        <dd class="col-sm-8"><a href="{{ route('courses.show', $enrollment->trainingCourse) }}" target="_blank">{{ $enrollment->trainingCourse->{'Course name'} }} <i class="fas fa-external-link-alt fa-xs"></i></a></dd>
                        <dt class="col-sm-4">Enrollment ID:</dt>
                        <dd class="col-sm-8">{{ $enrollment->EnrollmentID }}</dd>
                        <dt class="col-sm-4">Enrolled On:</dt>
                        <dd class="col-sm-8">{{ $enrollment->Date ? $enrollment->Date->format('Y-m-d H:i') : 'N/A' }}</dd>
                        <dt class="col-sm-4">Current Status:</dt>
                        <dd class="col-sm-8">
                             <span class="badge fs-6
                                @if($enrollment->Status == 'قيد التقدم') bg-primary
                                @elseif($enrollment->Status == 'مكتمل') bg-success
                                @elseif($enrollment->Status == 'ملغي') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ $enrollment->Status }}
                            </span>
                        </dd>
                        <dt class="col-sm-4">Completion Date:</dt>
                        <dd class="col-sm-8">{{ $enrollment->{'Complet Date'} ? \Carbon\Carbon::parse($enrollment->{'Complet Date'})->format('Y-m-d') : 'Not Yet' }}</dd>
                    </dl>
                    <hr class="my-3">
                    {{-- نموذج تغيير حالة التسجيل --}}
                    <form action="{{ route('company-manager.course-enrollments.update', $enrollment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label for="Status" class="form-label small">Change Enrollment Status:</label>
                                <select name="Status" id="Status" class="form-select form-select-sm @error('Status') is-invalid @enderror" required>
                                    <option value="قيد التقدم" {{ $enrollment->Status == 'قيد التقدم' ? 'selected' : '' }}>In Progress</option>
                                    <option value="مكتمل" {{ $enrollment->Status == 'مكتمل' ? 'selected' : '' }}>Completed</option>
                                    <option value="ملغي" {{ $enrollment->Status == 'ملغي' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('Status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col">
                                <label for="Complet_Date" class="form-label small">Completion Date (if Completed):</label>
                                <input type="date" name="Complet Date" id="Complet_Date" class="form-control form-control-sm @error('Complet Date') is-invalid @enderror" value="{{ old('Complet Date', $enrollment->{'Complet Date'} ? \Carbon\Carbon::parse($enrollment->{'Complet Date'})->format('Y-m-d') : '') }}">
                                @error('Complet Date') <div class="invalid-feedback">{{ $message }}</div> @enderror
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