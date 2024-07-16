@php
    $user = auth('customer')->user();
@endphp
<!-- resources/views/partials/top/user-dropdown.blade.php -->
<li class="nav-item dropdown">
    <a class="nav-icon pe-md-0 dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
        <img src="{{ $user->avatar_url }}" class="avatar img-fluid rounded" alt="{{ $user->name }}" />
    </a>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
        @if($user->hasPermission('user.profile'))
            <a class="dropdown-item" href="{{ route('public.user.profile') }}">
                <i class="align-middle me-1" data-feather="user"></i> {{ __('Profile') }}
            </a>
        @endif
        @if($user->hasPermission('settings.index'))
            <a class="dropdown-item" href="{{ route('public.settings.index') }}">
                <i class="align-middle me-1" data-feather="settings"></i> {{ __('Settings') }}
            </a>
        @endif
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('public.user.logout') }}">
            <i class="align-middle me-1" data-feather="log-out"></i> {{ __('Logout') }}
        </a>
    </div>
</li>
