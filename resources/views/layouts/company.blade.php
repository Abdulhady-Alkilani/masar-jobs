{{-- resources/views/layouts/company.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- إضافة بادئة Company للعنوان --}}
    <title>{{ config('app.name', 'Masar App') }} - Company - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> {{-- استخدام نفس الخط من app.blade.php --}}

    <!-- Scripts and Styles -->
    {{-- التأكد من أن هذه الملفات تقوم بتحميل Bootstrap CSS و JS --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- أي ستايلات إضافية للصفحة --}}
    @stack('styles')

    {{-- تضمين مكتبة الأيقونات (اختياري) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" /> --}}

    {{-- ستايل بسيط للشريط الجانبي (نفس المستخدم في admin.blade.php - يمكن نقله لملف CSS/SCSS مركزي) --}}
    <style>
        .sidebar {
            position: sticky; top: 0; height: 100vh; padding-top: 1rem;
            background-color: #f8f9fa; border-right: 1px solid #dee2e6; overflow-y: auto;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed; top: 0; bottom: 0; left: 0; z-index: 1030; width: 250px;
                transform: translateX(-100%); transition: transform 0.3s ease-in-out; padding-top: 3.5rem;
            }
            .sidebar.active { transform: translateX(0); }
            .sidebar-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background-color: rgba(0, 0, 0, 0.5); z-index: 1020; display: none; }
            .sidebar.active ~ .sidebar-overlay { display: block; }
        }
        .main-content { transition: margin-left 0.3s ease-in-out; }
    </style>
</head>
<body>
    <div id="app">
        {{-- Navbar مأخوذ من app.blade.phpتمام، سأقوم بتعديل ملف `layouts/company.blade.php` ليستخدم نفس هيكل وتنسيقات **Bootstrap** التي طبقناها على `layouts/admin.blade.php` ولوحة تحكم الأدمن، مع افتراض أنك تريد الحفاظ على تناسق التصميم باستخدام Bootstrap.

**الافتراضات:**

*   ملفات Bootstrap CSS و JS يتم تحميلها عبر `@vite`.
*   ملف `partials/_navigation.blade.php` إما يستخدم Bootstrap أو ستقوم بتعديله لاحقًا.
*   سنقوم بإنشاء ملف `partials/_company_sidebar_bootstrap.blade.php` للشريط الجانبي.

**`resources/views/layouts/company.blade.php` (مُحدّث لاستخدام Bootstrap)**

```blade
 --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container-fluid"> {{-- Use container-fluid --}}

                 {{-- زر لفتح/إغلاق الشريط الجانبي في الشاشات الصغيرة --}}
                 <button class="navbar-toggler me-2 d-lg-none" type="button" id="sidebarToggleBtnCompany"> {{-- معرف فريد --}}
                     <span class="navbar-toggler-icon"></span>
                 </button>

                <a class="navbar-brand" href="{{ route('company-manager.dashboard') }}"> {{-- رابط للوحة تحكم المدير --}}
                    {{ $company->Name ?? config('app.name', 'Company Panel') }} {{-- عرض اسم الشركة إن وجد --}}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar (يمكن وضع روابط خاصة بالشركة هنا) -->
                    <ul class="navbar-nav me-auto"> </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            {{-- ... --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->username ?? Auth::user()->name }} (Manager)
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                     <a class="dropdown-item" href="{{ route('company-manager.profile.show') }}"> {{-- رابط لملف الشركة --}}
                                        Company Profile
                                    </a>
                                     <a class="dropdown-item" href="{{ route('home') }}"> {{-- رابط للموقع العام --}}
                                        Back to Main Site
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form-company').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form-company" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- بداية محتوى الصفحة مع الشريط الجانبي --}}
        <div class="container-fluid">
            <div class="row">
                {{-- الشريط الجانبي --}}
                <nav id="companySidebar" class="col-lg-2 col-md-3 d-none d-lg-block sidebar"> {{-- معرف فريد --}}
                    <div class="position-sticky">
                        @include('partials._company_sidebar_bootstrap') {{-- اسم ملف جزئي للشريط --}}
                    </div>
                </nav>

                 {{-- Overlay للخلفية عند فتح الشريط الجانبي في الجوال --}}
                 <div class="sidebar-overlay d-lg-none" id="sidebarOverlayCompany"></div> {{-- معرف فريد --}}

                {{-- المحتوى الرئيسي --}}
                <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4 main-content">

                    {{-- عنوان الصفحة (اختياري) --}}
                     @hasSection('header')
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                             @yield('header')
                        </div>
                     @endif

                     {{-- عرض التنبيهات --}}
                     @include('partials._alerts') {{-- تأكد أنه يستخدم كلاسات Bootstrap --}}

                     {{-- المحتوى الفعلي للصفحة --}}
                    @yield('content')

                </main>
            </div>
        </div>
    </div>

    {{-- أكواد JavaScript إضافية --}}
    <script>
        {{-- resources/views/layouts/company.blade.php (مُحدّث لاستخدام Bootstrap) --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- إضافة بادئة Company Manager للعنوان --}}
    <title>{{ config('app.name', 'Masar App') }} - Company - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> {{-- استخدام نفس الخط من app.blade.php --}}

    <!-- Scripts and Styles -->
    @vite(['resources/// لفتح/إغلاق الشريط الجانبي الخاص بمدير الشركة
        const sidebarCompany = document.getElementById('companySidebar');
        const overlayCompany = document.getElementById('sidebarOverlayCompany');
        const toggleBtnCompany = document.getElementById('sidebarToggleBtnCompany');

        if (toggleBtnCompany && sidebarCompany) {
            toggleBtnCompany.addEventListener('click', () => {
                sidebarCompany.classList.toggle('active');
                if (overlayCompany) overlayCompany.style.display = sidebarCompany.classList.contains('active') ? 'block' : 'none';
            });
        }
        if (overlayCompany) {
             overlayCompany.addEventListener('click', () => {
                 sidebarCompany.classList.remove('active');
                 overlayCompany.style.display = 'none';
             });
        }
    </script>
    @stack('scripts')
</body>
</html>