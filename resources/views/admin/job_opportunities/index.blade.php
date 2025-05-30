@extends('layouts.admin')
{{-- !!! تعديل: تغيير العنوان --}}
@section('title', 'Manage Job Opportunities')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
         {{-- !!! تعديل: تغيير العنوان والأيقونة --}}
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-briefcase me-2"></i> {{ __('Manage Job Opportunities') }}</h2>
         {{-- !!! تعديل: تغيير الرابط والزر --}}
        <a href="{{ route('admin.job-opportunities.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Opportunity
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2"> {{-- flex-wrap للسماح بالالتفاف --}}
            <span class="card-title mb-0">Opportunities List</span>
            {{-- Search/Filter Form --}}
            <form action="{{ route('admin.job-opportunities.index') }}" method="GET" class="d-inline-flex flex-grow-1 justify-content-end">
                <input type="text" name="search" class="form-control form-control-sm me-2" style="max-width: 200px;" placeholder="Search title..." value="{{ request('search') }}">
                <select name="type" class="form-select form-select-sm me-2" style="max-width: 150px;">
                    <option value="">All Types</option>
                    <option value="وظيفة" {{ request('type') == 'وظيفة' ? 'selected' : '' }}>وظيفة</option>
                    <option value="تدريب" {{ request('type') == 'تدريب' ? 'selected' : '' }}>تدريب</option>
                </select>
                {{-- يمكنك إضافة فلتر للشركة هنا --}}
                {{-- <select name="company_id" class="form-select form-select-sm me-2" style="max-width: 200px;">...</select> --}}
                 <select name="status" class="form-select form-select-sm me-2" style="max-width: 150px;">
                    <option value="">All Statuses</option>
                    <option value="مفعل" {{ request('status') == 'مفعل' ? 'selected' : '' }}>مفعل</option>
                    <option value="معلق" {{ request('status') == 'معلق' ? 'selected' : '' }}>معلق</option>
                    <option value="محذوف" {{ request('status') == 'محذوف' ? 'selected' : '' }}>محذوف</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            </form>
        </div>

        <div class="card-body p-0">
             <div class="m-3"> @include('partials._alerts') </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Job Title</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>End Date</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- !!! تعديل: استخدام $jobOpportunities و $job --}}
                        @forelse ($jobOpportunities as $job)
                            <tr>
                                {{-- !!! تعديل: عرض بيانات $job --}}
                                <td class="ps-3">{{ Str::limit($job->{'Job Title'}, 40) }}</td>
                                {{-- عرض اسم الشركة من خلال العلاقة مع المستخدم --}}
                                <td>{{ $job->user->company->Name ?? ($job->user->username ?? 'N/A') }}</td>
                                <td><span class="badge bg-info">{{ $job->Type }}</span></td>
                                <td>
                                    <span class="badge {{ $job->Status === 'مفعل' ? 'bg-success' : ($job->Status === 'معلق' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ $job->Status }}
                                    </span>
                                </td>
                                 <td>{{ $job->{'End Date'} ? \Carbon\Carbon::parse($job->{'End Date'})->format('Y-m-d') : 'N/A' }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                         {{-- !!! تعديل: استخدام مسارات job-opportunities والمتغير $job --}}
                                        <a href="{{ route('admin.job-opportunities.show', $job) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.job-opportunities.edit', $job) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.job-opportunities.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- !!! تعديل: تغيير النص وعدد الأعمدة --}}
                                <td colspan="6" class="text-center text-muted py-4">
                                    No job opportunities found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> {{-- نهاية card-body --}}

        @if ($jobOpportunities->hasPages()) {{-- !!! تعديل: استخدام $jobOpportunities --}}
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                     {{-- !!! تعديل: استخدام $jobOpportunities --}}
                    {{ $jobOpportunities->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

    </div> {{-- نهاية card --}}
@endsection