@extends('layouts.admin')
@section('title', 'Create New Company')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i> Create New Company</h2>
         <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
         <div class="card-header">
            Enter Company Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                 {{-- تضمين النموذج الجزئي (بدون تمرير $company) --}}
                 @include('admin.companies._form', ['managers' => $managers]) {{-- تمرير المديرين المتاحين --}}
            </form>
        </div>
    </div>
@endsection