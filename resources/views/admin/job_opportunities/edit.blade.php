@extends('layouts.admin')
@section('title', 'Edit Opportunity: ' . $jobOpportunity->{'Job Title'})

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-edit me-2"></i> Edit Opportunity: {{ Str::limit($jobOpportunity->{'Job Title'}, 40) }}
        </h2>
         <a href="{{ route('admin.job-opportunities.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
         <div class="card-header">
            <i class="fas fa-info-circle me-2"></i> Opportunity Details
        </div>
        <div class="card-body">
             @include('partials._alerts')

             <form action="{{ route('admin.job-opportunities.update', $jobOpportunity) }}" method="POST">
                @method('PUT')
                 {{-- تمرير الفرصة الحالية وقائمة المديرين إلى النموذج الجزئي --}}
                @include('admin.job_opportunities._form', [
                    'jobOpportunity' => $jobOpportunity,
                    'managers' => $managers
                ])
            </form>
        </div>
    </div>
@endsection