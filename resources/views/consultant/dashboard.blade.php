{{-- resources/views/consultant/dashboard.blade.php --}}
@extends('layouts.consultant') {{-- تأكد أنه يرث من الـ Layout الصحيح (المُعد بـ Bootstrap) --}}

@section('title', 'Consultant Dashboard')

@section('header')
    {{-- عنوان بسيط داخل الـ Header الذي يوفره الـ Layout --}}
    <h2 class="h4 mb-0 text-primary">
        <i class="fas fa-chalkboard-teacher me-2"></i> {{ __('Consultant Dashboard') }}
    </h2>
@endsection

@section('content')
    {{-- لا نحتاج container هنا لأن الـ Layout يوفر padding --}}
    @include('partials._alerts') {{-- عرض التنبيهات --}}

    {{-- قسم الترحيب --}}
    <div class="card shadow-sm border-0 mb-4"> {{-- استخدام card بدون حدود ظاهرة --}}
        <div class="card-body bg-light rounded"> {{-- خلفية فاتحة للترحيب --}}
            <h5 class="card-title">Welcome back, {{ $consultant->first_name ?? Auth::user()->first_name }}!</h5>
            <p class="card-text text-muted mb-2">Manage your articles, profile, and insights from your dashboard.</p>
            <a href="{{ route('consultant.profile.show') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-user-edit me-1"></i> View/Edit Profile
            </a>
             <a href="{{ route('consultant.articles.create') }}" class="btn btn-sm btn-success ms-2">
                 <i class="fas fa-plus me-1"></i> Write New Article
             </a>
        </div>
    </div>

    {{-- قسم الإحصائيات --}}
    <div class="row g-3 mb-4"> {{-- استخدام row و gutters أصغر g-3 --}}
        {{-- Stat: Published Articles --}}
        <div class="col-md-6 col-xl-4">
            <div class="card text-center h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-center py-4">
                    <div class="mb-2">
                         <i class="fas fa-newspaper fa-2x text-info"></i> {{-- أيقونة أكبر --}}
                    </div>
                    <h6 class="card-title text-muted small text-uppercase mb-1">Published Articles</h6>
                    <p class="card-text h3 fw-bold text-info mb-0">{{ $stats['total_articles'] ?? 0 }}</p>
                    <a href="{{ route('consultant.articles.index') }}" class="stretched-link" aria-label="Manage Articles"></a>
                </div>
            </div>
        </div>

        {{-- Stat: Published Courses (إذا كان متاحًا) --}}
        @if(isset($stats['total_courses']))
            <div class="col-md-6 col-xl-4">
                <div class="card text-center h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                         <div class="mb-2">
                             <i class="fas fa-graduation-cap fa-2x text-success"></i>
                        </div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">Published Courses</h6>
                        <p class="card-text h3 fw-bold text-success mb-0">{{ $stats['total_courses'] ?? 0 }}</p>
                        {{-- <a href="{{ route('consultant.training-courses.index') }}" class="stretched-link" aria-label="Manage Courses"></a> --}}
                    </div>
                </div>
            </div>
        @endif

        {{-- Stat: Total Enrollments (إذا كان متاحًا) --}}
        @if(isset($stats['total_enrollments']))
            <div class="col-md-6 col-xl-4">
                <div class="card text-center h-100 shadow-sm">
                     <div class="card-body d-flex flex-column justify-content-center py-4">
                        <div class="mb-2">
                             <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <h6 class="card-title text-muted small text-uppercase mb-1">Total Enrollments</h6>
                        <p class="card-text h3 fw-bold text-primary mb-0">{{ $stats['total_enrollments'] ?? 0 }}</p>
                        {{-- <a href="{{ route('consultant.course-enrollments.index') }}" class="stretched-link" aria-label="View Enrollments"></a> --}}
                    </div>
                </div>
            </div>
        @endif
        {{-- يمكنك إضافة بطاقات إحصائيات أخرى --}}
    </div>


     {{-- قسم آخر المقالات --}}
     <div class="card shadow-sm">
         <div class="card-header d-flex justify-content-between align-items-center">
             <span><i class="fas fa-history me-2"></i> Your Recent Articles</span>
              <a href="{{ route('consultant.articles.index') }}" class="btn btn-sm btn-link text-secondary">View All</a>
         </div>
         <div class="list-group list-group-flush">
            @if(isset($recentArticles) && $recentArticles->count() > 0)
                 @foreach($recentArticles as $article)
                    <a href="{{ route('articles.show', $article) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" title="View public article">
                        <span>
                            <i class="fas fa-file-alt fa-fw text-muted me-2"></i>{{ $article->Title }}
                        </span>
                        <small class="text-muted">{{ $article->Date ? $article->Date->diffForHumans() : '' }}</small>
                    </a>
                 @endforeach
             @else
                <div class="list-group-item text-muted text-center py-3">You haven't published any articles yet.</div>
            @endif
        </div>
     </div>

@endsection