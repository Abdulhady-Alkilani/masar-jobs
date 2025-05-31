{{-- resources/views/layouts/graduate.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Masar App') }} - Graduate - @yield('title', 'Dashboard')</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')

    {{-- !!! CSS الخاص بالـ Sidebar يجب أن يكون هنا أو مستوردًا !!! --}}
    <style>
        body { overflow-x: hidden; }
        .sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; padding: 56px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); width: 240px; transition: transform .3s ease-in-out, width .3s ease-in-out;
            background-color: #f8f9fa; border-right: 1px solid #dee2e6; overflow-y: auto; }
        .sidebar .nav-link { font-weight: 500; color: #212529; padding: .75rem 1rem; /* ... */ }
        .sidebar .nav-link:hover { color: #0d6efd; background-color: #e9ecef; }
        .sidebar .nav-link.active { color: #0d6efd; font-weight: bold; }
        .sidebar .nav-link .fa-fw { width: 1.2em; }
        .sidebar-heading { /* ... */ }
        .main-content { margin-left: 240px; padding-top: 56px; transition: margin-left .3s ease-in-out; width: calc(100% - 240px); }
        @media (max-width: 991.98px) { /* lg breakpoint */
            .sidebar { transform: translateX(-100%); width: 0; }
            .sidebar.active { transform: translateX(0); width: 240px; z-index: 1030; }
            .main-content { margin-left: 0; width: 100%; }
            .sidebar-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1020; display: none; }
            .sidebar.active ~ .sidebar-overlay { display: block; }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white fixed-top shadow-sm">
            <div class="container-fluid">
                 <button class="navbar-toggler me-2 d-lg-none" type="button" id="sidebarToggleBtnGraduate">
                     <span class="navbar-toggler-icon"></span>
                 </button>
                <a class="navbar-brand" href="{{ route('graduate.dashboard') }}">
                     {{ config('app.name', 'Laravel') }} - Graduate
                </a>
                {{-- ... (باقي Navbar) ... --}}
                 <div class="collapse navbar-collapse" id="navbarSupportedContent"> <ul class="navbar-nav me-auto"> </ul> <ul class="navbar-nav ms-auto"> @guest @else <li class="nav-item dropdown"> <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{ Auth::user()->username ?? Auth::user()->name }} (Graduate) </a> <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"> <a class="dropdown-item" href="{{ route('graduate.profile.show') }}">My Profile</a> <a class="dropdown-item" href="{{ route('home') }}">Back to Site</a> <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-graduate').submit();"> {{ __('Logout') }} </a> <form id="logout-form-graduate" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form> </div> </li> @endguest </ul> </div>

            </div>
        </nav>

        <div class="d-flex">
            <nav id="graduateSidebar" class="sidebar">
                {{-- !!! تأكد أن هذا الملف موجود ومحتواه صحيح !!! --}}
                @include('partials._graduate_sidebar')
            </nav>
            <div class="sidebar-overlay d-lg-none" id="sidebarOverlayGraduate"></div>
            <main class="main-content p-4">
                 @hasSection('header') <div class="border-bottom pb-2 mb-3"> @yield('header') </div> @endif
                 @include('partials._alerts')
                 @yield('content')
            </main>
        </div>
    </div>

    {{-- !!! JavaScript للـ Sidebar يجب أن يكون هنا !!! --}}
    <script>
        const sidebarGraduate = document.getElementById('graduateSidebar');
        const overlayGraduate = document.getElementById('sidebarOverlayGraduate');
        const toggleBtnGraduate = document.getElementById('sidebarToggleBtnGraduate');
        if (toggleBtnGraduate && sidebarGraduate) { /* ... (نفس كود JS السابق) ... */ toggleBtnGraduate.addEventListener('click', () => { sidebarGraduate.classList.toggle('active'); if (overlayGraduate) overlayGraduate.style.display = sidebarGraduate.classList.contains('active') ? 'block' : 'none'; }); }
        if (overlayGraduate) { /* ... (نفس كود JS السابق) ... */ overlayGraduate.addEventListener('click', () => { sidebarGraduate.classList.remove('active'); overlayGraduate.style.display = 'none'; }); }
    </script>
    @stack('scripts')
</body>
</html>