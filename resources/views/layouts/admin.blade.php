{{-- resources/views/layouts/admin.blade.php (مُعدل لإضافة زر اللغة) --}}
<!doctype html>
{{-- !!! تغيير اتجاه الصفحة بناءً على اللغة !!! --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    {{-- ... (باقي محتوى head كما هو) ... --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Masar App') }} - Admin - @yield('title', __('general.dashboard'))</title> {{-- استخدام الترجمة --}}
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body { overflow-x: hidden; }
        .sidebar { /* ... (نفس ستايلات الـ Sidebar) ... */ }
        .main-content { /* ... (نفس ستايلات الـ Main Content) ... */ }
        /* تعديل بسيط للـ Sidebar في حالة RTL */
        html[dir="rtl"] .sidebar { left: auto; right: 0; box-shadow: inset 1px 0 0 rgba(0, 0, 0, .1); border-right: 0; border-left: 1px solid #dee2e6;}
        html[dir="rtl"] .main-content { margin-left: 0; margin-right: 240px; }
        @media (max-width: 991.98px) {
            html[dir="rtl"] .sidebar { transform: translateX(100%); }
            html[dir="rtl"] .sidebar.active { transform: translateX(0); }
            html[dir="rtl"] .main-content { margin-right: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top shadow-sm">
            <div class="container-fluid">
                {{-- ... (زر تبديل الـ Sidebar و العلامة التجارية كما هي) ... --}}
                 <button class="btn btn-outline-secondary me-2 d-lg-none" type="button" id="sidebarToggleBtn"> <i class="fas fa-bars"></i> </button>
                 <a class="navbar-brand" href="{{ route('admin.dashboard') }}"> <i class="fas fa-cogs me-1"></i> {{ config('app.name', 'Laravel') }} - @lang('general.admin_panel')</a>
                 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}"> <span class="navbar-toggler-icon"></span> </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"> </ul>
                    <ul class="navbar-nav ms-auto">
                        {{-- !!! زر تبديل اللغة !!! --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownLang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-globe me-1"></i> {{ strtoupper(app()->getLocale()) }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLang">
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English (EN)</a></li>
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">العربية (AR)</a></li>
                            </ul>
                        </li>
                        {{-- !!! نهاية زر تبديل اللغة !!! --}}

                        @guest
                            {{-- ... --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdownUser" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <i class="fas fa-user me-1"></i> {{ Auth::user()->username ?? Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                    <a class="dropdown-item" href="{{ route('home') }}"> <i class="fas fa-arrow-left fa-fw me-1"></i> Back to Site </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                         <i class="fas fa-sign-out-alt fa-fw me-1"></i> @lang('general.logout')
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- ... (باقي الـ Layout مع الـ Sidebar والمحتوى كما هو) ... --}}
         <div class="d-flex"> <nav id="adminSidebar" class="sidebar"> @include('partials._admin_sidebar') </nav> <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div> <main class="main-content p-4"> @hasSection('header') <div class="border-bottom pb-2 mb-3"> @yield('header') </div> @endif @include('partials._alerts') @yield('content') </main> </div>

    </div>
    {{-- ... (JavaScript للـ Sidebar كما هو) ... --}}
     <script> /* ... sidebar JS ... */ </script>
    @stack('scripts')
</body>
</html>