
@extends(DashHelper::viewPath('customer.account.master'))

@php
    Theme::set('pageTitle',  __('My Account'));
    $user = auth('customer')->user();
    
@endphp

@section('content')
<div class="row g-0">  
    <div class="col-12 d-flex flex-column">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md">
                    <h2 class="mb-4">{{ __('My Account') }}</h2>
                </div>
                <div class="col-md-auto justify-content-end">
                    <a href="{{ route('public.user.edit-account') }}" class="btn btn-primary">
                    {{ __('Edit profile') }} 
                    </a>
                </div>
            </div>
            <h3 class="card-title mt-4">{{ __('Profile Information') }}</h3>
            <div class="row g-3">
                <div class="col-md">
                    <div class="card-body text-center">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="img-fluid rounded-circle mb-2" width="128" height="128">
                        <h5 class="card-title mb-0">{{ $user->name }}</h5>
                        <div class="text-muted mb-2">Lead Developer</div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="col-md">
                        <div class="form-label">{{ __('Name') }}</div>
                        <input type="text" disabled class="form-control" value="{{ $user->name }}">
                    </div>
                    <div class="col-md">
                        <div class="form-label">{{ __('Phone') }}</div>
                        <input type="text" disabled class="form-control" value="{{ $user->phone }}">
                    </div>
                    <div class="col-md">
                        <div class="form-label">{{ __('Email') }}</div>
                        <input type="text" disabled class="form-control" value="{{ $user->email }}">
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>
@endsection