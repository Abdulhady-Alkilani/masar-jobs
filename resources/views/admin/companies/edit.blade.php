@extends('layouts.admin')
@section('title', 'Edit Company: ' . $company->Name)

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-edit me-2"></i> Edit Company: {{ $company->Name }}
        </h2>
         <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
     <div class="card shadow-sm">
         <div class="card-header">
            Update Company Details
        </div>
        <div class="card-body">
             @include('partials._alerts')
             <form action="{{ route('admin.companies.update', $company) }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 @method('PUT')
                 {{-- تضمين النموذج الجزئي مع بيانات الشركة الحالية --}}
                 @include('admin.companies._form', ['company' => $company])
             </form>
        </div>
    </div>
@endsection