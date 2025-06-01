@extends('layouts.admin')
@section('title', 'Manage Training Courses')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-graduation-cap me-2"></i> {{ __('Manage Training Courses') }}</h2>
        <a href="{{ route('admin.training-courses.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Course
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">Courses List</span>
             {{-- Add Search/Filter Form if needed --}}
        </div>

        <div class="card-body p-0">
             <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Course Name</th>
                            <th>Creator</th>
                            <th>Stage</th>
                            <th>Certificate</th>
                            <th>Site</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- !!! تعديل: استخدام $trainingCourses و $course --}}
                        @forelse ($trainingCourses as $course)
                            <tr>
                                {{-- !!! تعديل: عرض بيانات $course --}}
                                <td class="ps-3">{{ $course->{'Course name'} }}</td>
                                <td>{{ $course->creator->username ?? 'N/A' }}</td>
                                <td>{{ $course->Stage }}</td>
                                <td>{{ $course->Certificate }}</td>
                                <td>{{ $course->Site }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                         {{-- !!! تعديل: استخدام مسارات training-courses والمتغير $course --}}
                                        <a href="{{ route('admin.training-courses.show', $course) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.training-courses.edit', $course) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.training-courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No training courses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($trainingCourses->hasPages()) {{-- !!! تعديل: استخدام $trainingCourses --}}
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                     {{-- !!! تعديل: استخدام $trainingCourses --}}
                    {{ $trainingCourses->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection