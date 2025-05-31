{{-- resources/views/partials/_admin_sidebar_bootstrap.blade.php --}}
<div class="pt-3">
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i> @lang('general.dashboard')
            </a>
        </li>
    </ul>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted text-uppercase">
      <span>@lang('general.user_management')</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users fa-fw me-2"></i> @lang('general.manage_users')
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.company-requests.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.company-requests.index') }}">
                <i class="fas fa-file-signature fa-fw me-2"></i> @lang('general.company_requests')
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.companies.index') }}">
                <i class="fas fa-building fa-fw me-2"></i> @lang('general.manage_companies')
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.upgrade-requests.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.upgrade-requests.index') }}">
                <i class="fas fa-user-shield fa-fw me-2"></i> @lang('general.upgrade_requests')
            </a>
        </li>
    </ul>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted text-uppercase">
      <span>@lang('general.content_management')</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.articles.index') }}">
                <i class="fas fa-newspaper fa-fw me-2"></i> @lang('general.manage_articles')
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.job-opportunities.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.job-opportunities.index') }}">
                <i class="fas fa-briefcase fa-fw me-2"></i> @lang('general.manage_jobs')
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.training-courses.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.training-courses.index') }}">
                 <i class="fas fa-graduation-cap fa-fw me-2"></i> @lang('general.manage_courses')
            </a>
        </li>
    </ul>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted text-uppercase">
      <span>@lang('general.general_settings')</span>
    </h6>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.skills.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.skills.index') }}">
                <i class="fas fa-star fa-fw me-2"></i> @lang('general.manage_skills')
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.groups.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.groups.index') }}">
                 <i class="fas fa-layer-group fa-fw me-2"></i> @lang('general.manage_groups')
            </a>
        </li>
    </ul>
</div>