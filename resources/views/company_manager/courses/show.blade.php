@extends('layouts.company')
@section('title', 'Course Details: ' . Str::limit($trainingCourse->{'Course name'}, 30))

@section('header')
    <h2 class="h4 mb-0 text-primary">
        <i class="fas fa-info-circle me-2"></i> Course Details: {{ Str::limit($trainingCourse->{'Course name'}, 40) }}
    </h2>
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-chalkboard me-2"></i>{{ $trainingCourse->{'Course name'} }}</span>
             <span class="badge bg-secondary">{{ $trainingCourse->Stage }}</span>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Trainer(s):</dt>
                <dd class="col-sm-9">{{ $trainingCourse->{'Trainers name'} ?? 'N/A' }}
                    @if($trainingCourse->{'Trainers Site'})
                         (<a href="{{ $trainingCourse->{'Trainers Site'} }}" target="_blank" class="link-primary">Profile <i class="fas fa-external-link-alt fa-xs"></i></a>)
                    @endif
                </dd>

                <dt class="col-sm-3">Description:</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $trainingCourse->{'Course Description'} }}</dd>

                <dt class="col-sm-3">Location/Platform:</dt>
                <dd class="col-sm-9">{{ $trainingCourse->Site }}</dd>

                <dt class="col-sm-3">Certificate:</dt>
                <dd class="col-sm-9">
                     <span class="badge {{ $trainingCourse->Certificate === 'يوجد' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $trainingCourse->Certificate }}
                    </span>
                </dd>

                <dt class="col-sm-3">Start Date:</dt>
                <dd class="col-sm-9">{{ $trainingCourse->{'Start Date'} ? \Carbon\Carbon::parse($trainingCourse->{'Start Date'})->format('Y-m-d') : 'N/A' }}</dd>

                <dt class="col-sm-3">End Date:</dt>
                <dd class="col-sm-9">{{ $trainingCourse->{'End Date'} ? \Carbon\Carbon::parse($trainingCourse->{'End Date'})->format('Y-m-d') : 'N/A' }}</dd>

                <dt class="col-sm-3">Enrollment Link:</dt>
                <dd class="col-sm-9">
                    @if($trainingCourse->{'Enroll Hyper Link'})
                        <a href="{{ $trainingCourse->{'Enroll Hyper Link'} }}" target="_blank" class="link-primary">{{ $trainingCourse->{'Enroll Hyper Link'} }} <i class="fas fa-external-link-alt fa-xs"></i></a>
                    @else
                        N/A
                    @endif
                </dd>

                {{-- رابط لعرض المسجلين في هذه الدورة --}}
                <dt class="col-sm-3 mt-3"></dt>
                 <dd class="col-sm-9 mt-3">
                    <a href="{{ route('company-manager.course-enrollments.index', ['course_id' => $trainingCourse->CourseID]) }}" class="btn btn-sm btn-outline-info">
                         <i class="fas fa-users me-1"></i> View Enrolled Users
                    </a>
                </dd>
            </dl>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="{{ route('company-manager.training-courses.edit', $trainingCourse) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Course
        </a>
         <form action="{{ route('company-manager.training-courses.destroy', $trainingCourse) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Delete Course
            </button>
        </form>
        <a href="{{ route('company-manager.training-courses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Courses
        </a>
    </div>
@endsection