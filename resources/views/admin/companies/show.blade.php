@extends('layouts.admin')
@section('title', 'Company Details: ' . $company->Name)

@section('header')
     <h2 class="h4 mb-0 text-primary">
        <i class="fas fa-building me-2"></i> Company Details: {{ $company->Name }}
    </h2>
@endsection

@section('content')
    {{-- قسم معلومات الشركة (كما في الرد السابق) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-info-circle me-2"></i>Company Information</span>
             <span class="badge {{ $company->Status === 'Approved' ? 'bg-success' : ($company->Status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                {{ $company->Status ?? 'N/A' }}
            </span>
        </div>
        <div class="card-body">
            <dl class="row">
                {{-- ... (عرض بيانات الشركة كما في الرد السابق) ... --}}
                 <dt class="col-sm-3">Company ID</dt>
                 <dd class="col-sm-9">{{ $company->CompanyID }}</dd>
                 <dt class="col-sm-3">Company Name</dt>
                 <dd class="col-sm-9">{{ $company->Name }}</dd>
                 {{-- ... إلخ ... --}}
            </dl>
        </div>
    </div>

    {{-- !!! قسم عرض فرص العمل المرتبطة بالشركة !!! --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i class="fas fa-briefcase me-2"></i> Job Opportunities by {{ $company->Name }}
        </div>
        <div class="card-body p-0"> {{-- إزالة padding للسماح للجدول بأخذ العرض --}}
            @if($company->jobOpportunities && $company->jobOpportunities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Posted</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- !!! الآن نستخدم العلاقة مباشرة !!! --}}
                            @foreach($company->jobOpportunities as $job)
                                <tr>
                                    <td class="ps-3">{{ $job->{'Job Title'} }}</td>
                                    <td>{{ $job->Type }}</td>
                                    <td>
                                        <span class="badge {{ $job->Status === 'مفعل' ? 'bg-success' : 'bg-secondary' }}">{{ $job->Status }}</span>
                                    </td>
                                    <td>{{ $job->Date ? $job->Date->format('Y-m-d') : 'N/A' }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.job-opportunities.show', $job) }}" class="btn btn-xs btn-outline-info" title="View Job"><i class="fas fa-eye"></i></a>
                                         <a href="{{ route('admin.job-opportunities.edit', $job) }}" class="btn btn-xs btn-outline-warning" title="Edit Job"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card-body text-muted">
                    No job opportunities found for this company.
                     <a href="{{ route('admin.job-opportunities.create', ['company_id' => $company->CompanyID]) }}" class="btn btn-sm btn-link">Post one?</a> {{-- يمكنك تمرير ID الشركة هنا --}}
                </div>
            @endif
        </div>
    </div>

    {{-- أزرار الإجراءات للشركة نفسها --}}
    <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Company
        </a>
         <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Delete Company
            </button>
        </form>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection