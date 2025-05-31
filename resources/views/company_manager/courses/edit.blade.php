@extends('layouts.company')
@section('title', 'Edit Course: ' . Str::limit($trainingCourse->{'Course name'}, 30))

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-edit me-2"></i> Edit Course: {{ Str::limit($trainingCourse->{'Course name'}, 40) }}
        </h2>
         <a href="{{ route('company-manager.training-courses.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to Courses
        </a>
    </div>
@endsection

@section('content')
     <div class="card shadow-sm">
         <div class="card-header">
            Update Course Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('company-manager.training-courses.update', $trainingCourse) }}" method="POST">
                 @csrf
                 @method('PUT')
                 @include('company_manager.courses._form', ['trainingCourse' => $trainingCourse])
             </form>
        </div>
    </div>
@endsection