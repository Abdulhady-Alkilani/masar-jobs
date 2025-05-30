@extends('layouts.company')
@section('title', 'Create New Training Course')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i> Create New Training Course</h2>
         <a href="{{ route('company-manager.training-courses.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to Courses
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
         <div class="card-header">
            Enter Course Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('company-manager.training-courses.store') }}" method="POST">
                 @include('company_manager.courses._form')
            </form>
        </div>
    </div>
@endsection