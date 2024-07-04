@php

    Theme::set('pageTitle', __('Login'));
    Theme::layout('base2');
@endphp

{!! $form->renderForm() !!}
    