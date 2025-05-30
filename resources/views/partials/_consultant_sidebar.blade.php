{{-- الشريط الجانبي الخاص بالاستشاري بتنسيق Bootstrap --}}
<div class="pt-3">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('consultant.dashboard') ? 'active fw-bold' : '' }}" href="{{ route('consultant.dashboard') }}">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('consultant.profile.*') ? 'active fw-bold' : '' }}" href="{{ route('consultant.profile.show') }}">
                <i class="fas fa-user fa-fw me-2"></i> My Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('consultant.articles.*') ? 'active fw-bold' : '' }}" href="{{ route('consultant.articles.index') }}">
                <i class="fas fa-newspaper fa-fw me-2"></i> My Articles
            </a>
        </li>
        {{-- أضف روابط أخرى هنا --}}
    </ul>
</div>