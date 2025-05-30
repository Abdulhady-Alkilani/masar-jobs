@extends('layouts.admin')
@section('title', 'Create New Job Opportunity')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i> Create New Job Opportunity</h2>
         <a href="{{ route('admin.job-opportunities.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
         <div class="card-header">
            Enter Opportunity Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('admin.job-opportunities.store') }}" method="POST">
                 {{-- تضمين النموذج الجزئي وتمرير قائمة الشركات والمديرين --}}
                 @include('admin.job_opportunities._form', ['companies' => $companies, 'managers' => $managers])
            </form>
        </div>
    </div>
@endsection