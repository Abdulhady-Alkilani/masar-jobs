@extends('layouts.admin')
@section('title', 'Course Details: ' . $trainingCourse->{'Course name'})

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-graduation-cap me-2"></i> Course Details: {{ Str::limit($trainingCourse->{'Course name'}, 40) }}
        </h2>
         <a href="{{ route('admin.training-courses.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    {{-- Card لمعلومات الدورة --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-info-circle me-2"></i>Course Information</span>
            <span class="badge bg-info">{{ $trainingCourse->Stage ?? 'N/A' }}</span>
        </div>
        <div class="card-body">
            {{-- استخدام Definition List لعرض البيانات --}}
            <dl class="row">
                <dt class="col-sm-3">Course Name</dt>
                <dd class="col-sm-9">{{ $trainingCourse->{'Course name'} }}</dd>

                <dt class="col-sm-3">Creator</dt>
                <dd class="col-sm-9">
                    @if($trainingCourse->creator)
                        <a href="{{ route('admin.users.show', $trainingCourse->creator) }}">{{ $trainingCourse->creator->username ?? 'N/A' }}</a>
                    @else
                        N/A
                    @endif
                </dd>
                {{-- باقي تفاصيل الدورة --}}
                <dt class="col-sm-3">Trainer(s)</dt>
                 <dd class="col-sm-9">{{ $trainingCourse->{'Trainers name'} ?? 'N/A' }}</dd>
                 <dt class="col-sm-3">Trainer(s) Site</dt>
                <dd class="col-sm-9">@if(filter_var($trainingCourse->{'Trainers Site'}, FILTER_VALIDATE_URL))<a href="{{ $trainingCourse->{'Trainers Site'} }}" target="_blank">{{ $trainingCourse->{'Trainers Site'} }}</a>@else{{ $trainingCourse->{'Trainers Site'} ?? 'N/A' }}@endif</dd>
                <dt class="col-sm-3">Location/Platform</dt>
                <dd class="col-sm-9">{{ $trainingCourse->Site }}</dd>
                <dt class="col-sm-3">Certificate</dt>
                <dd class="col-sm-9"><span class="badge {{ $trainingCourse->Certificate === 'يوجد' ? 'bg-success' : 'bg-secondary' }}">{{ $trainingCourse->Certificate }}</span></dd>
                <dt class="col-sm-3">Enrollment Link</dt>
                <dd class="col-sm-9">@if(filter_var($trainingCourse->{'Enroll Hyper Link'}, FILTER_VALIDATE_URL))<a href="{{ $trainingCourse->{'Enroll Hyper Link'} }}" target="_blank">{{ $trainingCourse->{'Enroll Hyper Link'} }}</a>@else{{ $trainingCourse->{'Enroll Hyper Link'} ?? 'N/A' }}@endif</dd>
                <dt class="col-sm-3">Start Date</dt>
                <dd class="col-sm-9">{{ $trainingCourse->{'Start Date'} ? \Carbon\Carbon::parse($trainingCourse->{'Start Date'})->format('Y-m-d') : 'N/A' }}</dd>
                <dt class="col-sm-3">End Date</dt>
                <dd class="col-sm-9">{{ $trainingCourse->{'End Date'} ? \Carbon\Carbon::parse($trainingCourse->{'End Date'})->format('Y-m-d') : 'N/A' }}</dd>
                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $trainingCourse->{'Course Description'} }}</dd>
                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $trainingCourse->created_at->format('Y-m-d H:i') }}</dd>
                <dt class="col-sm-3">Last Updated</dt>
                <dd class="col-sm-9">{{ $trainingCourse->updated_at->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>

     {{-- Card لعرض المسجلين --}}
     <div class="card shadow-sm mb-4">
        <div class="card-header">
             <i class="fas fa-user-check me-2"></i> Enrolled Users ({{ $trainingCourse->enrollments->count() }})
        </div>
         <div class="card-body p-0">
             <div class="table-responsive">
                 <table class="table table-hover align-middle mb-0">
                     <thead class="table-light">
                         <tr> <th class="ps-3">User</th> <th>Email</th> <th>Enrolled At</th> <th>Status</th> <th>Completed At</th> </tr>
                     </thead>
                     <tbody>
                        @forelse($trainingCourse->enrollments as $enrollment)
                        <tr>
                            <td class="ps-3">@if($enrollment->user)<a href="{{ route('admin.users.show', $enrollment->user) }}">{{ $enrollment->user->username ?? 'N/A' }}</a>@else User not found @endif</td>
                            <td>{{ $enrollment->user->email ?? 'N/A' }}</td>
                            <td>{{ $enrollment->Date ? $enrollment->Date->format('Y-m-d') : 'N/A' }}</td>
                            <td><span class="badge {{ $enrollment->Status === 'قيد التقدم' ? 'bg-primary' : ($enrollment->Status === 'مكتمل' ? 'bg-success' : 'bg-secondary') }}">{{ $enrollment->Status }}</span></td>
                            <td>{{ $enrollment->{'Complet Date'} ? \Carbon\Carbon::parse($enrollment->{'Complet Date'})->format('Y-m-d') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No enrollments yet.</td></tr>
                        @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
    </div>

    {{-- أزرار الإجراءات الرئيسية --}}
    <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="{{ route('admin.training-courses.edit', $trainingCourse) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Course
        </a>
        <form action="{{ route('admin.training-courses.destroy', $trainingCourse) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Delete Course</button>
        </form>
         <a href="{{ route('admin.training-courses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
    </div>
@endsection