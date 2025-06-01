@extends('layouts.graduate') {{-- أو layouts.app --}}
@section('title', 'My Course Enrollments')

@section('header')
    <h2 class="h4 mb-0 text-primary"><i class="fas fa-book-reader me-2"></i> My Course Enrollments</h2>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <span class="card-title mb-0">Courses You Are Enrolled In</span>
             {{-- يمكنك إضافة فلترة حسب الحالة هنا --}}
            {{-- <form> ... filter by status ... </form> --}}
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Course Name</th>
                            <th>Trainer / Provider</th>
                            <th>Enrolled On</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr>
                                <td class="ps-3">
                                    @if($enrollment->trainingCourse)
                                        <a href="{{ route('courses.show', $enrollment->trainingCourse) }}" title="View Course Details">
                                            {{ $enrollment->trainingCourse->{'Course name'} ?? 'N/A' }}
                                        </a>
                                    @else
                                        Course Not Found
                                    @endif
                                </td>
                                <td>
                                    {{-- عرض اسم المدرب أو المنشئ --}}
                                    @if($enrollment->trainingCourse?->{'Trainers name'})
                                         {{ $enrollment->trainingCourse->{'Trainers name'} }}
                                    @elseif($enrollment->trainingCourse?->creator)
                                         {{ $enrollment->trainingCourse->creator->username ?? 'N/A' }}
                                    @else
                                         N/A
                                    @endif
                                </td>
                                <td>{{ $enrollment->Date ? $enrollment->Date->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $enrollment->Status === 'مكتمل' ? 'bg-success' : ($enrollment->Status === 'قيد التقدم' ? 'bg-primary' : 'bg-secondary') }}">
                                        {{ $enrollment->Status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('graduate.enrollments.show', $enrollment) }}" class="btn btn-outline-primary" title="View Enrollment Details"><i class="fas fa-eye"></i></a>
                                        {{-- زر إلغاء التسجيل (إذا كانت الحالة تسمح) --}}
                                        @if($enrollment->Status !== 'مكتمل' && $enrollment->Status !== 'ملغي') {{-- مثال للحالات المسموح بها --}}
                                            <form action="{{ route('graduate.enrollments.destroy', $enrollment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this enrollment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Cancel Enrollment"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                     <p class="mb-2">You are not enrolled in any courses yet.</p>
                                     <a href="{{ route('courses.index') }}" class="btn btn-success btn-sm">
                                         <i class="fas fa-search me-1"></i> Browse Courses
                                     </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> {{-- نهاية card-body --}}

        @if ($enrollments->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $enrollments->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div> {{-- نهاية card --}}
@endsection