@extends('layouts.company')
@section('title', 'Post New Job Opportunity')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i> Post New Job Opportunity</h2>
         <a href="{{ route('company-manager.job-opportunities.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to My Opportunities
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
            <form action="{{ route('company-manager.job-opportunities.store') }}" method="POST">
                 {{-- لا نحتاج لتمرير $company أو $managers هنا، لأن الكنترولر سيستخدم شركة المدير الحالي --}}
                 @include('company_manager.jobs._form')
            </form>
        </div>
    </div>
@endsection