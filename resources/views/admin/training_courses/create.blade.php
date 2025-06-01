@extends('layouts.admin')
@section('title', 'Create New Training Course')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb, Riyadh Center, Zoom">
        @error('Site') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

     {{-- Enrollment Link --}}
    <div class="col-md-6">
        <label for="Enroll_Hyper_Link" class="form-label">Enrollment Link</label>
        <input type="url" class="form-control @error('Enroll Hyper Link') is-invalid @enderror" id="Enroll_Hyper_Link" name="Enroll Hyper Link" value="{{ old('Enroll Hyper Link', $trainingCourse->{'Enroll Hyper Link'} ?? '') }}" placeholder="https://...">
        @error('Enroll Hyper Link') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Stage --}}
    <div class="col-md-4">
        <label for="Stage" class="form-0 text-primary">
            <i class="fas fa-plus-circle me-2"></i> {{ __('Create New Training Course') }}
        </h2>
         <a href="{{ route('admin.training-courses.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section-label">Level (Stage) <span class="text-danger">*</span></label>
        <select class="form-select @error('Stage') is-invalid @enderror" id="Stage" name="Stage" required>
            <option value="" disabled {{ old('Stage', $trainingCourse->Stage ?? '') == '' ? 'selected' : '' }}>-- Select Level --</option>
            <option value="مبتدئ" {{('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-info-circle me-2"></i> Course Details
        </div>
        <div class="card-body">
             @include('partials._alerts')

             <form action="{{ route('admin.training-courses.store') }}" method="POST">
                {{-- تمرير قائمة المنشئين ($creators) إلى النموذج الجزئي --}}
                @include('admin.training_courses._form', ['creators' => $creators])
            </form>
        </div>
    </div>
@endsection