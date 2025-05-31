@extends('layouts.consultant')
@section('title', 'Course Enrollments')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-user-check me-2"></i> Course Enrollments</h2>
        {{-- يمكن إضافة زر للعودة للداشبورد أو إدارة الدورات --}}
        {{-- <a href="{{ route('consultant.dashboard') }}" class="btn btn-sm btn-outline-secondary">Back to Dashboard</a> --}}
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="card-title mb-0 me-2">Enrolled Users List</span>
            {{-- Filter Form --}}
            <form action="{{ route('consultant.course-enrollments.index') }}" method="GET" class="d-inline-flex flex-grow-1 flex-wrap justify-content-end gap-2">
                <select name="course_id" class="form-select form-select-sm" style="max-width: 250px;">
                    <option value="">All My Courses</option>
                    @foreach($consultantCourses as $course)
                        <option value="{{ $course->CourseID }}" {{ request('course_id') == $course->CourseID ? 'selected' : '' }}>
                            {{ Str::limit($course->{'Course name'}, 40) }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="">All Statuses</option>
                    <option value="قيد التقدم" {{ request('status') == 'قيد التقدم' ? 'selected' : '' }}>In Progress</option>
                    <option value="مكتمل" {{ request('status') == 'مكتمل' ? 'selected' : '' }}>Completed</option>
                    <option value="ملغي" {{ request('status') == 'ملغي' ? 'selected' : '' }}>Cancelled</option> {{-- إذا كان لديك حالة إلغاء --}}
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <a href="{{ route('consultant.course-enrollments.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filter">Clear</a>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Student Name</th>
                            <th>Course Name</th>
                            <th>Enrolled On</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr>
                                <td class="ps-3">{{ $enrollment->user->username ?? 'N/A' }}</td>
                                <td>{{ $enrollment->trainingCourse ? Str::limit($enrollment->trainingCourse->{'Course name'}, 40) : 'N/A' }}</td>
                                <td>{{ $enrollment->Date ? $enrollment->Date->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $enrollment->Status === 'مكتمل' ? 'bg-success' : ($enrollment->Status === 'قيد التقدم' ? 'bg-primary' : 'bg-secondary') }}">
                                        {{ $enrollment->Status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('consultant.course-enrollments.show', $enrollment) }}" class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                     {{-- لا يوجد عادة زر تعديل أو حذف للمسجلين من قبل الاستشاري --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No enrollments found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($enrollments->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $enrollments->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection