@extends('layouts.app') {{-- أو layout عام آخر إذا كان لديك --}}

@section('title', 'Job Opportunities & Training')

@section('header')
    {{-- يمكنك إضافة عنوان كبير هنا إذا أردت --}}
    <h2 class="h3 mb-0 text-primary text-center">
        <i class="fas fa-briefcase me-2"></i> Discover Opportunities
    </h2>
    <p class="text-muted text-center">Find your next career move or training program.</p>
@endsection

@section('content')
<div class="container py-4">
    @include('partials._alerts')

    {{-- قسم الفلترة والبحث --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="{{ route('jobs.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="search" class="form-label">Search Keywords</label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="Job title, skills, description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">Opportunity Type</label>
                        <select class="form-select form-select-sm" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="وظيفة" {{ request('type') == 'وظيفة' ? 'selected' : '' }}>وظيفة (Job)</option>
                            <option value="تدريب" {{ request('type') == 'تدريب' ? 'selected' : '' }}>تدريب (Training)</option>
                        </select>
                    </div>
                    {{-- يمكنك إضافة فلاتر أخرى هنا (مثل الموقع، الشركة) --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- عرض بطاقات فرص العمل --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse ($jobOpportunities as $job)
            <div class="col">
                <div class="card h-100 shadow-hover lift"> {{-- lift لإضافة تأثير ظل عند المرور --}}
                    {{-- يمكنك إضافة صورة للشركة أو صورة افتراضية هنا --}}
                    {{-- <img src="..." class="card-img-top" alt="..."> --}}
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none stretched-link">
                                {{ $job->{'Job Title'} }}
                            </a>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted small">
                             {{-- عرض اسم الشركة (تحتاج لعلاقة company في مودل JobOpportunity) --}}
                             @if($job->company)
                                <i class="fas fa-building me-1"></i>{{ $job->company->Name }}
                             @elseif($job->user && $job->user->company)
                                <i class="fas fa-building me-1"></i>{{ $job->user->company->Name }}
                             @else
                                 <i class="fas fa-user-tie me-1"></i> {{ $job->user->username ?? 'Unknown Poster' }}
                             @endif
                        </h6>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($job->{'Job Description'}, 120) }}
                        </p>
                        <div class="mt-auto">
                            <span class="badge {{ $job->Type === 'وظيفة' ? 'bg-success' : 'bg-info text-dark' }} me-2">{{ $job->Type }}</span>
                            @if($job->Site)
                                <span class="badge bg-secondary"><i class="fas fa-map-marker-alt me-1"></i>{{ $job->Site }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 text-muted small">
                        Posted: {{ $job->Date ? $job->Date->diffForHumans() : 'N/A' }}
                        @if($job->{'End Date'})
                            | Ends: {{ $job->{'End Date'}->format('M d, Y') }}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    No job opportunities found matching your criteria.
                </div>
            </div>
        @endforelse
    </div>

    {{-- روابط الـ Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $jobOpportunities->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection

@push('styles')
<style>
    .shadow-hover:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transform: translateY(-2px);
        transition: all .2s ease-in-out;
    }
    .lift {
        transition: box-shadow .25s ease,transform .25s ease,-webkit-box-shadow .25s ease,-webkit-transform .25s ease;
    }
</style>
@endpush