@php
    $user = auth('customer')->user();
    $notifications = $user->lastUnreadNotifications()->get();
    $unreadCount = $user->countUnreadNotifications();
@endphp

<!-- resources/views/partials/alerts-dropdown.blade.php -->
<li class="nav-item dropdown">
    <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
        <div class="position-relative">
            <i class="align-middle" data-feather="bell"></i>
            @if($unreadCount > 0)
                <span class="indicator">{{ $unreadCount }}</span>
            @endif
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
        <div class="dropdown-menu-header">
            {{ $unreadCount }} {{ __('New Notifications') }}
        </div>
        <div class="list-group">
            @if ($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <a href="{{ $notification->url ?? '#' }}" class="list-group-item">
                        <div class="row g-0 align-items-center">
                            <div class="col-2">
                                <i class="text-primary" data-feather="{{ $notification->icon ?? 'bell' }}"></i>
                            </div>
                            <div class="col-10">
                                <div class="text-dark">{{ $notification->title }}</div>
                                <div class="text-muted small mt-1">{{ $notification->content }}</div>
                                <div class="text-muted small mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="text-center small text-gray-500">{{ __('No new notifications') }}</div>
            @endif
        </div>
        <div class="dropdown-menu-footer">
            <a href="#" class="text-muted">{{ __('Show all notifications') }}</a>
        </div>
    </div>
</li>