{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Masar App') }} - @yield('title', 'Welcome')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <div id="app" class="flex-grow-1">
        {{-- Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    {{ config('app.name', 'Masar App') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Jobs</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('articles.index') ? 'active' : '' }}" href="{{ route('articles.index') }}">Articles</a></li>
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="btn btn-primary btn-sm ms-lg-2 mt-2 mt-lg-0 px-3 py-2" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarUserDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->username }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                                    <li><a class="dropdown-item" href="{{ route('home') }}">Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form-guest').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form-guest" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- المحتوى الرئيسي للصفحة --}}
        @yield('content')

        {{-- عرض هذا القسم فقط في الصفحة الرئيسية أو إذا لم يتم تجاوز المحتوى --}}
        @if(Route::currentRouteName() == 'welcome' || !View::hasSection('content_override'))
            <div class="container py-5">
                @include('partials._alerts')

                {{-- قسم أحدث فرص العمل --}}
                <section class="mb-5">
                    <h2 class="text-center display-5 mb-5 fw-bold text-primary">Latest Job Opportunities</h2>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @forelse ($latestJobs ?? [] as $job)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 lift">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                                        <i class="fas fa-briefcase fa-3x text-secondary opacity-50"></i>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-semibold">
                                            <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none stretched-link">
                                                {{ $job->{'Job Title'} }}
                                            </a>
                                        </h5>
                                        <h6 class="card-subtitle mb-2 text-muted small">
                                             @if($job->company) <i class="fas fa-building me-1"></i>{{ $job->company->Name }}
                                             @elseif($job->user && $job->user->company) <i class="fas fa-building me-1"></i>{{ $job->user->company->Name }}
                                             @else <i class="fas fa-user-tie me-1"></i> {{ $job->user->username ?? 'Unknown' }} @endif
                                        </h6>
                                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($job->{'Job Description'}, 90) }}</p>
                                        <span class="badge {{ $job->Type === 'وظيفة' ? 'bg-success-subtle text-success-emphasis' : 'bg-info-subtle text-info-emphasis' }} p-2 mt-auto align-self-start">{{ $job->Type }}</span>
                                    </div>
                                     <div class="card-footer bg-white text-muted small border-top-0 pt-0 pb-3">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $job->Site }}
                                        <span class="mx-1">|</span>
                                        <i class="fas fa-calendar-alt me-1 text-secondary"></i> {{ $job->Date ? $job->Date->diffForHumans() : '' }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><p class="text-muted text-center py-4">No job opportunities available at the moment.</p></div>
                        @endforelse
                    </div>
                    @if(Route::has('jobs.index'))
                        <div class="text-center mt-4 pt-2">
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">Browse All Jobs <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    @endif
                </section>

                <hr class="my-5 opacity-25">

                {{-- قسم أحدث الدورات --}}
                <section>
                    <h2 class="text-center display-5 mb-5 fw-bold text-success">Latest Training Courses</h2>
                     <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @forelse ($latestCourses ?? [] as $course)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 lift">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                                         <i class="fas fa-graduation-cap fa-3x text-secondary opacity-50"></i>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-semibold">
                                            <a href="{{ route('courses.show', $course) }}" class="text-decoration-none stretched-link">
                                                {{ $course->{'Course name'} }}
                                            </a>
                                        </h5>
                                         <h6 class="card-subtitle mb-2 text-muted small"><i class="fas fa-chalkboard-teacher me-1"></i> {{ $course->{'Trainers name'} ?? 'N/A' }}</h6>
                                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($course->{'Course Description'}, 90) }}</p>
                                        <div class="mt-auto d-flex justify-content-between align-items-center">
                                             <span class="badge bg-secondary-subtle text-secondary-emphasis p-2">{{ $course->Stage }}</span>
                                             <span class="badge {{ $course->Certificate === 'يوجد' ? 'bg-primary-subtle text-primary-emphasis' : 'bg-light text-muted' }} p-2"><i class="fas fa-certificate me-1"></i> {{ $course->Certificate }}</span>
                                        </div>
                                    </div>
                                     <div class="card-footer bg-white text-muted small border-top-0 pt-0 pb-3">
                                        <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $course->Site }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><p class="text-muted text-center py-4">No training courses available at the moment.</p></div>
                        @endforelse
                    </div>
                    @if(Route::has('courses.index'))
                        <div class="text-center mt-4 pt-2">
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2">Browse All Courses <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    @endif
                </section>
            </div>
        @endif {{-- نهاية شرط عرض الأقسام العامة --}}

    </div> {{-- نهاية div#app --}}

    <footer class="text-center py-4 bg-white border-top mt-auto">
        <small class="text-muted">© {{ date('Y') }} {{ config('app.name', 'Masar App') }}. All rights reserved.</small>
    </footer>

    @stack('scripts')
</body>
</html>