@php
    Theme::set('pageTitle',  SeoHelper::getTitle());
@endphp

<section class="about-area about-p pt-120 pb-120 p-relative fix">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            @if($backgroundImage = theme_option('authentication_reset_password_background_image'))
                                <div class="col-lg-6 d-none d-lg-block bg-reset-password-image">
                                    <img src="{{ RvMedia::getImageURL($backgroundImage) }}" alt="{{ __('Reset password') }}" />
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">{{ __('Reset password') }}</h1>
                                    </div>
                                    <form class="user" method="POST" action="{{ route('customer.password.reset.update') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                                id="email" name="email" placeholder="{{ __('Enter your email address') }}" value="{{ old('email', $email) }}" required>
                                            {!! Form::error('email', $errors) !!}
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user {{ $errors->has('password') ? ' is-invalid' : '' }}" 
                                                id="password" name="password" placeholder="{{ __('Enter your new password') }}" required>
                                            {!! Form::error('password', $errors) !!}
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" 
                                                id="password_confirmation" name="password_confirmation" placeholder="{{ __('Confirm your new password') }}" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            {{ __('Reset Password') }}
                                        </button>
                                        
                                        <hr>
                                        <div class="text-center">
                                            {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\Dashplugin\Models\Customer::class) !!}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
