{{-- resources/views/layouts/admin.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Masar App') }} - Admin - @yield('title', __('general.dashboard'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts and Styles (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Font Awesome (Optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom Styles for Admin Layout -->
    <style>
        body {
            overflow-x: hidden; /* لمنع التمرير الأفقي غير المرغوب فيه */
            /* مساحة للـ navbar الثابت. اضبطها لتناسب ارتفاع الـ navbar الفعلي لديك */
            /* تم نقل الـ padding إلى .main-content لجعل الـ navbar والـ sidebar يمتدان بالكامل */
        }

        /* الـ Navbar سيكون fixed-top من كلاس Bootstrap */

        .sidebar {
            position: fixed; /* يجعله ثابتًا على اليسار/اليمين */
            top: 0; /* يبدأ من الأعلى */
            bottom: 0;
            left: 0; /* افتراضي لـ LTR */
            z-index: 100; /* ليكون خلف الـ navbar إذا كان الـ navbar يستخدم z-index أعلى */
            padding-top: 56px; /* مساحة للـ navbar الثابت (ارتفاع navbar Bootstrap الافتراضي) */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); /* ظل خفيف على اليمين */
            width: 240px; /* عرض الشريط الجانبي */
            transition: transform .3s ease-in-out, width .3s ease-in-out;
            background-color: #343a40; /* لون الشريط الجانبي الداكن (Bootstrap dark) */
            color: #adb5bd; /* لون النص الفاتح للروابط */
            overflow-y: auto; /* تمرير إذا كان المحتوى أطول */
        }
        /* تعديلات لاتجاه RTL */
        html[dir="rtl"] .sidebar {
            left: auto; /* إلغاء تحديد اليسار */
            right: 0; /* تحديد اليمين */
            box-shadow: inset 1px 0 0 rgba(0, 0, 0, .1); /* ظل على اليسار */
            border-right: 0; /* إزالة الحد الأيمن إذا كان موجودًا */
            border-left: 1px solid #212529; /* حد أغمق قليلاً على اليسار */
        }

        .sidebar .nav-link {
            font-weight: 500;
            color: #adb5bd; /* لون الروابط الافتراضي */
            padding: 0.65rem 1rem; /* تعديل padding الروابط قليلاً */
            transition: background-color .15s ease-in-out, color .15s ease-in-out;
            border-left: 3px solid transparent; /* للرابط النشط في LTR */
        }
        html[dir="rtl"] .sidebar .nav-link {
            border-left: 0;
            border-right: 3px solid transparent; /* للرابط النشط في RTL */
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.focus { /* إضافة حالة focus */
            color: #fff;
            background-color: #495057; /* لون عند مرور الماوس أو التركيز */
        }

        .sidebar .nav-link.active {
            color: #fff; /* لون النص للرابط النشط */
            background-color: #0d6efd; /* لون خلفية الرابط النشط (Bootstrap primary) */
            border-left-color: #0d6efd; /* لون الحد الجانبي للرابط النشط في LTR */
        }
        html[dir="rtl"] .sidebar .nav-link.active {
            border-left-color: transparent;
            border-right-color: #0d6efd; /* لون الحد الجانبي للرابط النشط في RTL */
        }

        .sidebar .nav-link .fa-fw {
            width: 1.25em; /* لمحاذاة الأيقونات بشكل أفضل */
            margin-right: 0.5rem;
        }
        html[dir="rtl"] .sidebar .nav-link .fa-fw {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        .sidebar-heading {
            font-size: .70rem; /* تصغير حجم الخط قليلاً */
            text-transform: uppercase;
            padding: .75rem 1rem .25rem; /* تعديل padding */
            margin-top: 0.75rem; /* تعديل الهامش */
            color: #868e96; /* لون أغمق قليلاً للعناوين (Bootstrap secondary text) */
            font-weight: 600; /* جعلها أثقل قليلاً */
        }
        .sidebar-heading:first-of-type { /* استخدام first-of-type لتطبيق على أول عنوان فقط */
            margin-top: 0.25rem; /* تقليل الهامش العلوي لأول عنوان */
        }

        /* منطقة المحتوى الرئيسية */
        .main-content-wrapper {
            display: flex; /* لاستخدام Flexbox لتنظيم الـ Sidebar والمحتوى */
            padding-top: 56px; /* مساحة للـ navbar الثابت في الأعلى */
        }

        .main-content {
            margin-left: 240px; /* نفس عرض الشريط الجانبي لـ LTR */
            transition: margin-left .3s ease-in-out, margin-right .3s ease-in-out;
            width: calc(100% - 240px); /* أخذ المساحة المتبقية */
            flex-grow: 1; /* لضمان أن المحتوى يملأ المساحة */
            overflow-y: auto; /* يسمح بالتمرير إذا كان المحتوى طويلاً */
            height: calc(100vh - 56px); /* لتفعيل التمرير إذا لزم الأمر */
        }
        html[dir="rtl"] .main-content {
            margin-left: 0;
            margin-right: 240px; /* نفس عرض الشريط الجانبي لـ RTL */
        }


        /* Responsive: إخفاء الشريط الجانبي على الشاشات الصغيرة */
        @media (max-width: 991.98px) { /* Bootstrap lg breakpoint */
            .sidebar {
                transform: translateX(-100%); /* إخفاء لـ LTR */
                width: 0; /* لإخفاء العرض بشكل كامل عند الإغلاق */
            }
            html[dir="rtl"] .sidebar {
                transform: translateX(100%); /* إخفاء لـ RTL */
            }

            .sidebar.active {
                transform: translateX(0); /* إظهار */
                width: 240px; /* إعادة العرض */
                z-index: 1030; /* ليكون فوق الـ overlay */
            }

            .main-content {
                margin-left: 0 !important; /* المحتوى يأخذ العرض الكامل عند إخفاء الـ sidebar */
                margin-right: 0 !important;
                width: 100%;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1020; /* ليكون خلف الـ sidebar وأمام المحتوى */
                display: none; /* Hidden by default */
            }
            .sidebar.active ~ .sidebar-overlay { /* ~ هو General Sibling Combinator */
                 display: block;
             }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        {{-- Navbar ثابت في الأعلى --}}
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top shadow-sm">
            <div class="container-fluid">
                 <button class="btn btn-outline-secondary me-2 d-lg-none" type="button" id="sidebarToggleBtn" aria-label="Toggle sidebar">
                     <i class="fas fa-bars"></i>
                 </button>
                 <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                     <i class="fas fa-cogs me-1"></i> {{ config('app.name', 'Laravel') }} - @lang('general.admin_panel')
                 </a>
                 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('Toggle navigation')">
                     <span class="navbar-toggler-icon"></span>
                 </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"> </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownLang" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="fas fa-globe me-1"></i> {{ strtoupper(app()->getLocale()) }} </a> <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLang"> <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English (EN)</a></li> <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">العربية (AR)</a></li> </ul> </li>
                        @guest @else <li class="nav-item dropdown"> <a id="navbarDropdownUser" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-user me-1"></i> {{ Auth::user()->username ?? Auth::user()->name }} </a> <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser"> <a class="dropdown-item" href="{{ route('home') }}"> <i class="fas fa-arrow-left fa-fw me-1"></i> Back to Site </a> <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i class="fas fa-sign-out-alt fa-fw me-1"></i> @lang('general.logout') </a> <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form> </div> </li> @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- بداية محتوى الصفحة مع الشريط الجانبي --}}
        <div class="main-content-wrapper"> {{-- تم إضافة هذا الـ div --}}
            <nav id="adminSidebar" class="sidebar">
                @include('partials._admin_sidebar') {{-- تأكد أن هذا هو الاسم الصحيح --}}
            </nav>
            <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>
            <main class="main-content p-4">
                 @hasSection('header') <div class="border-bottom pb-2 mb-3"> @yield('header') </div> @endif
                 @include('partials._alerts')
                 @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebarAdmin = document.getElementById('adminSidebar');
        const overlayAdmin = document.getElementById('sidebarOverlay');
        const toggleBtnAdmin = document.getElementById('sidebarToggleBtn');

        if (toggleBtnAdmin && sidebarAdmin) {
            toggleBtnAdmin.addEventListener('click', () => {
                sidebarAdmin.classList.toggle('active');
                if (overlayAdmin) overlayAdmin.style.display = sidebarAdmin.classList.contains('active') ? 'block' : 'none';
            });
        }
        if (overlayAdmin) {
             overlayAdmin.addEventListener('click', () => {
                 sidebarAdmin.classList.remove('active');
                 overlayAdmin.style.display = 'none';
             });
        }
    </script>
    @stack('scripts')
</body>
</html>