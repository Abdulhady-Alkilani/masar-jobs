{{-- الشريط الجانبي الخاص بالخريج بتنسيق Bootstrap مُحسّن --}}
<div class="pt-3"> {{-- إضافة padding علوي --}}
    {{-- قسم رئيسي للداشبورد والبروفايل --}}
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('graduate.dashboard') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('graduate.dashboard') }}">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i>
                My Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('graduate.profile.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('graduate.profile.show') }}">
                <i class="fas fa-user-circle fa-fw me-2"></i>
                My Profile
            </a>
        </li>
    </ul>

    {{-- عنوان قسم البحث عن الفرص --}}
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted text-uppercase">
      <span>Opportunities</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('jobs.index') }}">
                <i class="fas fa-briefcase fa-fw me-2"></i>
                Browse Jobs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('courses.index') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('courses.index') }}">
                 <i class="fas fa-graduation-cap fa-fw me-2"></i>
                Browse Courses
            </a>
        </li>
    </ul>

     {{-- عنوان قسم أنشطتي --}}
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted text-uppercase">
      <span>My Activity</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('graduate.applications.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('graduate.applications.index') }}">
                <i class="fas fa-file-alt fa-fw me-2"></i>
                My Job Applications
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('graduate.enrollments.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('graduate.enrollments.index') }}">
                <i class="fas fa-book-reader fa-fw me-2"></i>
                My Course Enrollments
            </a>
        </li>
    </ul>

    {{-- عنوان قسم التوصيات --}}
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted text-uppercase">
      <span>Personalized</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('graduate.recommendations.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('graduate.recommendations.index') }}">
                <i class="fas fa-magic fa-fw me-2"></i>
                Recommendations
            </a>
        </li>
    </ul>

    {{-- يمكنك إضافة رابط للعودة للموقع الرئيسي أو الخروج هنا أيضًا --}}
    {{-- <hr class="my-3">
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link link-secondary" href="{{ route('home') }}">
                <i class="fas fa-home fa-fw me-2"></i> Back to Main Site
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link link-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('graduate-sidebar-logout-form').submit();">
               <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
            </a>
            <form id="graduate-sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul> --}}
</div>