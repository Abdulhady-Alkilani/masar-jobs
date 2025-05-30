@extends('layouts.graduate') {{-- أو layouts.app --}}
@section('title', 'My Job Applications')

@section('header')
    <h2 class="h4 mb-0 text-primary"><i class="fas fa-file-alt me-2"></i> My Job Applications</h2>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <span class="card-title mb-0">Your Submitted Applications</span>
             {{-- يمكنك إضافة فلترة حسب الحالة هنا --}}
             {{-- <form> ... filter by status ... </form> --}}
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Job Title</th>
                            <th>Company</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr>
                                <td class="ps-3">
                                    @if($application->jobOpportunity)
                                        <a href="{{ route('jobs.show', $application->jobOpportunity) }}" title="View Job Details">
                                            {{ $application->jobOpportunity->{'Job Title'} ?? 'N/A' }}
                                        </a>
                                    @else
                                        Job Not Found
                                    @endif
                                </td>
                                <td>
                                    {{-- للوصول لاسم الشركة، نحتاج لتحميلها إما عبر JobOpportunity أو User --}}
                                    {{-- نفترض أن الوظيفة مرتبطة بمستخدم (مدير) لديه شركة --}}
                                     @if($application->jobOpportunity?->user?->company)
                                        {{ $application->jobOpportunity->user->company->Name }}
                                     @elseif($application->jobOpportunity?->company) {{-- إذا كانت الوظيفة مرتبطة مباشرة بالشركة --}}
                                         {{ $application->jobOpportunity->company->Name }}
                                     @else
                                         N/A
                                     @endif
                                </td>
                                <td>{{ $application->Date ? $application->Date->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    {{-- تنسيق الحالة --}}
                                     <span class="badge {{
                                         match ($application->Status) {
                                             'Pending' => 'bg-secondary',
                                             'Reviewed' => 'bg-info text-dark',
                                             'Shortlisted' => 'bg-primary',
                                             'Hired' => 'bg-success',
                                             'Rejected' => 'bg-danger',
                                             default => 'bg-light text-dark',
                                         }
                                     }}">
                                         {{ $application->Status ?? 'N/A' }}
                                     </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                         <a href="{{ route('graduate.applications.show', $application) }}" class="btn btn-outline-primary" title="View Application Details"><i class="fas fa-eye"></i></a>
                                         {{-- زر سحب الطلب (إذا كانت الحالة تسمح) --}}
                                         @if(in_array($application->Status, ['Pending', 'Reviewed'])) {{-- مثال للحالات المسموح بها --}}
                                            <form action="{{ route('graduate.applications.destroy', $application) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to withdraw this application?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Withdraw Application"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                     <p class="mb-2">You haven't applied for any jobs yet.</p>
                                     <a href="{{ route('jobs.index') }}" class="btn btn-success btn-sm">
                                         <i class="fas fa-search me-1"></i> Find Job Opportunities
                                     </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> {{-- نهاية card-body --}}

        @if ($applications->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $applications->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div> {{-- نهاية card --}}
@endsection