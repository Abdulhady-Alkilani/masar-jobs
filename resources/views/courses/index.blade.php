@extends('layouts.app')

@section('title', 'Training Courses')

@section('header')
    {{-- يمكنك وضع العنوان في الـ header إذا كان layout app يدعمه --}}
    {{-- <h2 class="h4 text-primary mb-0"><i class="fas fa-graduation-cap me-2"></i> Training Courses</h2> --}}
@endsection

@section('content')
<div class="container mt-4">
     {{-- وضع العنوان هنا إذا لم يكن في الـ header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary"><i class="fas fa-graduation-cap me-2"></i> Training Courses</h1>
         {{-- زر إضافة دورة جديدة (يظهر فقط للمصرح لهم) --}}
         @can('create', App\Models\TrainingCourse::class) {{-- مثال Policy --}}
            <a href="{{-- route('training-courses.create') أو المسار الصحيح --}}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> Add New Course
            </a>
         @endcan
    </div>

    @include('partials._alerts')

    {{-- قسم الفلترة/البحث (اختياري) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="{{ route('courses.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md">
                    <label for="search" class="form-label visually-hidden">Search Courses</label>
                    <input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="Search by name, description, trainer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-auto">
                    <label for="stage" class="form-label visually-hidden">Stage</label>
                    <select name="stage" id="stage" class="form-select form-select-sm">
                        <option value="">All Stages</option>
                        <option value="مبتدئ" {{ request('stage') == 'مبتدئ' ? 'selected' : '' }}>Beginner</option>
                        <option value="متوسط" {{ request('stage') == 'متوسط' ? 'selected' : '' }}>Intermediate</option>
                        <option value="متقدم" {{ request('stage') == 'متقدم' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>
                 {{-- يمكنك إضافة فلاتر أخرى (مثل الموقع، شهادة) --}}
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Clear</a>
                </div>
            </form>
        </div>
    </div>


    {{-- شبكة عرض الدورات --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse ($trainingCourses as $course)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    {{-- يمكنك إضافة صورة للدورة هنا إذا كان المودل يدعمها --}}
                    {{-- <img src="..." class="card-img-top" alt="..."> --}}
                     <div class="card-header bg-primary text-white">
                         <h5 class="card-title mb-0 h6">{{ $course->{'Course name'} }}</h5>
                    </div>
                    <div class="card-body pb-2">
                        <p class="card-text small text-muted mb-2">
                            <i class="fas fa-chalkboard-teacher fa-fw me-1"></i> {{ $course->{'Trainers name'} ?? 'N/A' }}
                            @if($course->{'Trainers Site'})
                                <a href="{{ $course->{'Trainers Site'} }}" target="_blank" class="ms-1"><i class="fas fa-link fa-xs"></i></a>
                            @endif
                        </p>
                         <p class="card-text small mb-2">
                            <i class="fas fa-map-marker-alt fa-fw me-1 text-muted"></i> {{ $course->Site ?? 'N/A' }}
                             | <i class="fas fa-certificate fa-fw me-1 {{ $course->Certificate == 'يوجد' ? 'text-success' : 'text-muted' }}"></i> {{ $course->Certificate == 'يوجد' ? 'Certificate' : 'No Certificate' }}
                             | <span class="badge bg-secondary">{{ $course->Stage ?? 'N/A' }}</span>
                        </p>
                        <p class="card-text small flex-grow-1">
                            {{ Str::limit($course->{'Course Description'}, 100) }}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 text-center">
                         <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                             <i class="fas fa-info-circle me-1"></i> View Details
                         </a>
                         {{-- زر التسجيل للخريج --}}
                         @auth
                            @if(Auth::user()->type === 'خريج')
                            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="d-inline ms-1">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-user-plus me-1"></i> Enroll Now
                                </button>
                            </form>
                            @endif
                         @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center" role="alert">
                    No training courses found matching your criteria.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $trainingCourses->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection