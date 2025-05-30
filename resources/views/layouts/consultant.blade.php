{{-- resources/views/layouts/consultant.blade.php (النسخة الصحيحة بـ Bootstrap) --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Masar App') }} - Consultant - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> {{-- توحيد الخط --}}

    <!-- Scripts and Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js']) {{-- تحميل Bootstrap --}}
    @stack('styles')

    {{-- تضمين Font Awesome (اختياري) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    {{-- ستايل بسيط للشريط الجانبي (يمكن نقله لملف CSS/SCSS مركزي) --}}
    <style>
        body { overflow-x: hidden; }
        .sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding: 56px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); width: 240px; transition: transform .3s ease-in-out, width .3s ease-in-out;
            background-color: #f8f9fa; /* لون فاتح أو داكن حسب رغبتك */ border-right: 1px solid #dee2e6; overflow-y: auto; }
        .sidebar .nav-link { font-weight: 500; color: #212529; /* لون أغمق للخلفية الفاتحة */ padding: .75rem 1rem; transition: background-color .15s ease-in-out, color .15s ease-in-out; }
        .sidebar .nav-link:hover { color: #0d6efd; background-color: #e9ecef; }
        .sidebar .nav-link.active { color: #0d6efd; font-weight: bold; }
        .sidebar .nav-link .fa-fw { width: 1.2em; }
        .sidebar-heading { font-size: .75rem; text-transform: uppercase; padding: .5rem 1rem; margin-top: 1rem; color: #6c757d; }
        .main-content { margin-left: 240px; padding-top: 56px; transition: margin-left .3s ease-in-out; width: calc(100% - 240px); }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); width: 0; }
            .sidebar.active { transform: translateX(0); width: 240px; z-index: 1030; }
            .main-content { margin-left: 0; width: 100%; }
            .sidebar-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1020; display: none; }
            .sidebar.active ~ .sidebar-overlay { display: block; }
        }
    </style>

    @stack('styles') {{-- للسماح بإضافة ستايلات أخرى --}}
</head>
<body>
    <div id="app">
        {{-- Navbar ثابت في الأعلى --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white fixed-top shadow-sm"> {{-- Navbar فاتح وثابت --}}
            <div class="container-fluid">
                 {{-- زر تبديل الشريط الجانبي --}}
                 <button class="navbar-toggler me-2 d-lg-none" type="button" id="sidebarToggleBtnConsultant"> {{-- معرف فريد --}}
                     <span class="navbar-toggler-icon"></span>
                 </button>

                <a class="navbar-brand" href="{{ route('consultant.dashboard') }}">
                     {{-- <i class="fas fa-chalkboard-teacher me-1"></i> --}} {{-- أيقونة اختيارية --}}
                     {{ config('app.name', 'Laravel') }} - Consultant
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"> </ul> {{-- Navbar يسار فارغ --}}
                    <ul class="navbar-nav ms-auto"> {{-- Navbar يمين --}}
                        @guest
                            {{-- لا يجب أن يظهر هذا إذا كان الـ Layout محمي --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{-- <i class="fas fa-user me-1"></i> --}} {{-- أيقونة اختيارية --}}
                                    {{ Auth::user()->username ?? Auth::user()->name }} (Consultant)
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('consultant.profile.show') }}">My Profile</a>
                                    <a class="dropdown-item" href="{{ route('home') }}">Back to Site</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form-consultant').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form-consultant" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- بداية محتوى الصفحة مع الشريط الجانبي --}}
        <div class="d-flex">
            {{-- الشريط الجانبي --}}
            <nav id="consultantSidebar" class="sidebar"> {{-- معرف فريد --}}
                {{-- !!! تأكد أن هذا الملف موجود ويستخدم Bootstrap !!! --}}
                @include('partials._consultant_sidebar') {{-- أو _consultant_sidebar_bootstrap --}}
            </nav>

            {{-- Overlay للخلفية --}}
            <div class="sidebar-overlay d-lg-none" id="sidebarOverlayConsultant"></div> {{-- معرف فريد --}}

            {{-- المحتوى الرئيسي --}}
            <main class="main-content p-4"> {{-- إضافة padding --}}
                 @hasSection('header')
                    <div class="border-bottom pb-2 mb-3">
                         @yield('header')
                    </div>
                 @endif
                 @include('partials._alerts') {{-- تأكد أنه يستخدم Bootstrap Alerts --}}
                 @yield('content')
            </main>
        </div>
    </div>

    {{-- أكواد JavaScript --}}
    <script>
        // لفتح/إغلاق الشريط الجانبي للاستشاري
        const sidebarConsultant = document.getElementById('consultantSidebar');
        const overlayConsultant = document.getElementById('sidebarOverlayConsultant');
        const toggleBtnConsultant = document.getElementById('sidebarToggleBtnConsultant');

        if (toggleBtnConsultant && sidebarConsultant) {
            toggleBtnConsultant.addEventListener('click', () => {
                sidebarConsultant.classList.toggle('active');
                if (overlayConsultant) overlayConsultant.style.display = sidebarConsultant.classList.contains('active') ? 'block' : 'none';
            });
        }
        if (overlayConsultant) {
             overlayConsultant.addEventListener('click', () => {
                 sidebarConsultant.classList.remove('active');
                 overlayConsultant.style.display = 'none';
             });
        }
    </script>
    @stack('scripts') {{-- للسماح بإضافة scripts أخرى --}}
</body>
</html>