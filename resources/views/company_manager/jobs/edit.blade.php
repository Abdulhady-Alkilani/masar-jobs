@extends('layouts.company')
@section('title', 'Edit Opportunity: ' . Str::limit($jobOpportunity->{'Job Title'}, 30))

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-edit me-2"></i> Edit Opportunity: {{ Str::limit($jobOpportunity->{'Job Title'}, 40) }}
        </h2>
         <a href="{{ route('company-manager.job-opportunities.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to My Opportunities
        </a>
    </div>
@endsection

@section('content')
     <div class="card shadow-sm">
         <div class="card-header">
            Update Opportunity Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('company-manager.job-opportunities.update', $jobOpportunity) }}" method="POST">
                 @csrf
                 @method('PUT')
                 @include('company_manager.jobs._form', ['jobOpportunity' => $jobOpportunity])
             </form>
        </div>
    </div>
@endsection