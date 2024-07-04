@php
    Theme::set('pageTitle', __('Register'));
    Theme::layout('base2');
@endphp

{!! $form->renderForm() !!}
