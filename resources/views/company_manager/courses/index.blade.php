@extends('layouts.company')
@section('title', 'Manage My Training Courses')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-graduation-cap me-2"></i> My Training Courses</h2>
        <a href="{{ route('company-manager.training-courses.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Course
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <span class="card-title mb-0">Courses List</span>
            {{-- يمكنك إضافة فلترة هنا إذا لزم الأمر --}}
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Course Name</th>
                            <th>Trainer(s)</th>
                            <th>Stage</th>
                            <th>Location/Platform</th>
                            <th>Certificate</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trainingCourses as $course)
                            <tr>
                                <td class="ps-3">{{ Str::limit($course->{'Course name'}, 50) }}</td>
                                <td>{{ Str::limit($course->{'Trainers name'}, 30) ?? 'N/A' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $course->Stage }}</span></td>
                                <td>{{ $course->Site }}</td>
                                <td>
                                    <span class="badge {{ $course->Certificate === 'يوجد' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $course->Certificate }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('company-manager.training-courses.show', $course) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('company-manager.training-courses.edit', $course) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('company-manager.training-courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    You haven't created any training courses yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($trainingCourses->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $trainingCourses->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection