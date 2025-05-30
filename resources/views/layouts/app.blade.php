<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Welcome')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts and Styles -->
    {{-- التأكد من أن app.scss يستورد Bootstrap 5 --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Font Awesome (اختياري) -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" ... /> --}}

    <!-- Custom Styles for Sidebar (يمكن نقلها لملف CSS/SCSS مركزي) -->
    <style>
        body { overflow-x: hidden; }
        .sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding: 56px 0 0; /* Navbar height */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); width: 240px; transition: transform .3s ease-in-out, width .3s ease-in-out;
            background-color: #f8f9fa; /* Light sidebar example */ border-right: 1px solid #dee2e6; overflow-y: auto; }
        .sidebar .nav-link { font-weight: 500; color: #212529; padding: .75rem 1rem; transition: background-color .15s ease-in-out, color .15s ease-in-out; }
        .sidebar .nav-link:hover { color: #0d6efd; background-color: #e9ecef; }
        .sidebar .nav-link.active { color: #0d6efd; font-weight: bold; /* أو تغيير الخلفية */ }
        .sidebar .nav-link .fa-fw { width: 1.2em; }
        .sidebar-heading { font-size: .75rem; text-transform: uppercase; padding: .5rem 1rem; margin-top: 1rem; color: #6c757d; }
        .main-content { margin-left: 240px; padding-top: 56px; /* Navbar height */ transition: margin-left .3s ease-in-out; width: calc(100% - 240px); }
        /* إخفاء الشريط الجانبي افتراضيًا إذا لم يكن هناك محتوى له */
        .sidebar:empty { display: none; }
        .sidebar:empty + .sidebar-overlay + .main-content { margin-left: 0; width: 100%; }

        @media (max-width: 991.98px) { /* lg breakpoint */
            .sidebar { transform: translateX(-100%); width: 0; }
             /* فقط أظهر الشريط الجانبي النشط إذا كان يحتوي على محتوى */
             .sidebar.active:not(:empty) { transform: translateX(0); width: 240px; z-index: 1030; }
            .main-content { margin-left: 0; width: 100%; }
            .sidebar-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1020; display: none; }
             .sidebar.active:not(:empty) ~ .sidebar-overlay { display: block; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="app">
        {{-- Navbar ثابت في الأعلى --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white fixed-top shadow-sm">
            <div class="container-fluid">

                 {{-- زر تبديل الشريط الجانبي (يظهر فقط في الشاشات الصغيرة وإذا كان هناك sidebar) --}}
                 {{-- استخدام @hasSection للتحقق من وجود محتوى للـ sidebar --}}
                 @hasSection('sidebar-content')
                     <button class="navbar-toggler me-2 d-lg-none" type="button" id="sidebarToggleBtnApp"> {{-- معرف فريد للـ App Layout --}}
                         {{-- <i class="fas fa-bars"></i> --}} {{-- استخدام أيقونة --}}
                         <span class="navbar-toggler-icon"></span> {{-- أو أيقونة Bootstrap القياسية --}}
                     </button>
                 @endif

                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        {{-- روابط عامة يمكن وضعها هنا إذا لم تكن في الشريط الجانبي --}}
                         <li class="nav-item">
                             <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">{{ __('Jobs') }}</a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{ route('courses.index') }}">{{ __('Courses') }}</a>
                         </li>
                          <li class="nav-item">
                             <a class="nav-link {{ request()->routeIs('articles.index') ? 'active' : '' }}" href="{{ route('articles.index') }}">{{ __('Articles') }}</a>
                         </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{-- <i class="fas fa-user me-1"></i> --}}
                                    {{ Auth::user()->username ?? Auth::user()->name }}
                                    {{-- عرض نوع المستخدم (اختياري) --}}
                                    @if(Auth::user()->type)
                                     <span class="badge bg-secondary ms-1">{{ Auth::user()->type }}</span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                     {{-- رابط لوحة التحكم الرئيسية بناءً على الدور --}}
                                     @switch(Auth::user()->type)
                                         @case('Admin')
                                             <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                             @break
                                         @case('مدير شركة')
                                             <a class="dropdown-item" href="{{ route('company-manager.dashboard') }}">Company Dashboard</a>
                                             @break
                                         @case('خبير استشاري')
                                              <a class="dropdown-item" href="{{ route('consultant.dashboard') }}">Consultant Dashboard</a>
                                             @break
                                         @case('خريج')
                                             <a class="dropdown-item" href="{{ route('graduate.dashboard') }}">Graduate Dashboard</a>
                                             @break
                                         @default
                                              <a class="dropdown-item" href="{{ route('home') }}">Dashboard</a>
                                     @endswitch

                                      {{-- رابط الملف الشخصي بناءً على الدور --}}
                                     @switch(Auth::user()->type)
                                         @case('Admin')
                                             {{-- لا يوجد رابط بروفايل خاص للأدمن عادة --}}
                                             @break
                                         @case('مدير شركة')
                                             <a class="dropdown-item" href="{{ route('company-manager.profile.show') }}">Company Profile</a>
                                             @break
                                         @case('خبير استشاري')
                                              <a class="dropdown-item" href="{{ route('consultant.profile.show') }}">My Profile</a>
                                             @break
                                         @case('خريج')
                                             <a class="dropdown-item" href="{{ route('graduate.profile.show') }}">My Profile</a>
                                             @break
                                     @endswitch

                                    <hr class="dropdown-divider"> {{-- فاصل --}}

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- بداية محتوى الصفحة مع الشريط الجانبي (إذا كان موجودًا) --}}
        <div class="d-flex">
            {{-- الشريط الجانبي --}}
            {{-- الـ CSS سيقوم بإخفائه إذا كان @yield('sidebar-content') فارغًا --}}
            <nav id="appSidebar" class="sidebar"> {{-- معرف فريد --}}
                 @yield('sidebar-content') {{-- مكان لوضع محتوى الشريط الجانبي المحدد في الـ Views الفرعية --}}
            </nav>

             {{-- Overlay للخلفية (يظهر فقط إذا كان الشريط الجانبي نشطًا على الجوال) --}}
             <div class="sidebar-overlay d-lg-none" id="sidebarOverlayApp"></div> {{-- معرف فريد --}}

            {{-- المحتوى الرئيسي --}}
            <main class="main-content p-4 flex-grow-1"> {{-- flex-grow-1 لملء المساحة --}}
                 @hasSection('header')
                    <div class="border-bottom pb-2 mb-3">
                         @yield('header')
                    </div>
                 @endif
                 @include('partials._alerts')
                 @yield('content')
            </main>
        </div>
    </div>

    {{-- أكواد JavaScript للتحكم في الشريط الجانبي --}}
    <script>
        const sidebarApp = document.getElementById('appSidebar');
        const overlayApp = document.getElementById('sidebarOverlayApp');
        const toggleBtnApp = document.getElementById('sidebarToggleBtnApp');

        // التحقق من وجود العناصر قبل إضافة المستمعين
        if (toggleBtnApp && sidebarApp) {
            toggleBtnApp.addEventListener('click', () => {
                sidebarApp.classList.toggle('active');
                if (overlayApp) overlayApp.style.display = sidebarApp.classList.contains('active') ? 'block' : 'none';
            });
        }
        if (overlayApp) {
             overlayApp.addEventListener('click', () => {
                 sidebarApp.classList.remove('active');
                 overlayApp.style.display = 'none';
             });
        }
    </script>
    @stack('scripts')
</body>
</html>