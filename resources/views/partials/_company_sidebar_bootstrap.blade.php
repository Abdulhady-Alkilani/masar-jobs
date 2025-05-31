{{-- الشريط الجانبي الخاص بمدير الشركة بتنسيق Bootstrap --}}
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('company-manager.dashboard') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('company-manager.dashboard') }}">
            <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
        </a>
    </li>
     <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('company-manager.profile.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('company-manager.profile.show') }}"> {{-- تأكد من اسم المسار الصحيح --}}
            <i class="fas fa-building fa-fw me-2"></i> Company Profile
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('company-manager.job-opportunities.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('company-manager.job-opportunities.index') }}">
             <i class="fas fa-briefcase fa-fw me-2"></i> Job Opportunities
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('company-manager.job-applications.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('company-manager.job-applications.index') }}">
             <i class="fas fa-users fa-fw me-2"></i> Job Applications
        </a>
    </li>
    {{-- إذا كان مسموحًا بإدارة الدورات --}}
    {{-- <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('company-manager.training-courses.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('company-manager.training-courses.index') }}">
             <i class="fas fa-graduation-cap fa-fw me-2"></i> Training Courses
        </a>
    </li> --}}
    {{-- <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('company-manager.course-enrollments.*') ? 'active fw-bold' : 'link-secondary' }}" href="{{ route('company-manager.course-enrollments.index') }}">
             <i class="fas fa-user-check fa-fw me-2"></i> Course Enrollments
        </a>
    </li> --}}
    {{-- أضف المزيد من الروابط حسب الحاجة --}}
</ul>