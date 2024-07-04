@php
    $user = auth('customer')->user();
    $messages = $user->lastUnreadMessages()->get();
    $unreadCount = $user->countUnreadMessages();
@endphp

<!-- resources/views/partials/top/messages-dropdown.blade.php -->
<li class="nav-item dropdown">
    <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
        <div class="position-relative">
            <i class="align-middle" data-feather="message-square"></i>
            @if($unreadCount > 0)
                <span class="indicator">{{ $unreadCount }}</span>
            @endif
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
        <div class="dropdown-menu-header">
            <div class="position-relative">
                {{ $unreadCount }} {{ __('New Messages') }}
            </div>
        </div>
        <div class="list-group">
            @if ($messages->count() > 0)
                @foreach($messages as $message)
                    <a href="{{ $message->url ?? '#' }}" class="list-group-item">
                        <div class="row g-0 align-items-center">
                            <div class="col-2">
                                <img src="{{ asset('img/avatars/avatar-placeholder.jpg') }}" class="avatar img-fluid rounded-circle" alt="{{ $message->sender_name }}">
                            </div>
                            <div class="col-10 ps-2">
                                <div class="text-dark">{{ $message->title }}</div>
                                <div class="text-muted small mt-1">{{ $message->content }}</div>
                                <div class="text-muted small mt-1">{{ $message->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="text-center small text-gray-500">{{ __('No new messages') }}</div>
            @endif
        </div>
        <div class="dropdown-menu-footer">
            <a href="#" class="text-muted">{{ __('Show all messages') }}</a>
        </div>
    </div>
</li>

