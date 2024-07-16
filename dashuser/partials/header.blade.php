<!DOCTYPE html>
<html  lang="{{ app()->getLocale() }}">
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <meta name="csrf-token" content="{{ csrf_token() }}" >
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="canonical" href="https://demo.adminkit.io/pages-blank.html" />
    @if( PageTitle::getTitle() )
        <title>{{ PageTitle::getTitle() }}</title>
    @endif
    {!! Theme::header() !!}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    {!! Assets::renderHeader() !!}
    
    <script type="text/javascript">
        var BotbleVariables = BotbleVariables || {};

    @if (Auth::guard('customer')->check() || Auth::guard()->check())
        BotbleVariables.languages = {
            @if(DashHelper::isRenderTable())
            tables: {{ Js::from(trans('core/base::tables')) }},
            @endif
            notices_msg: {{ Js::from(trans('core/base::notices')) }},
            pagination: {{ Js::from(trans('pagination')) }},
        };
    @else
        BotbleVariables.languages = {
            notices_msg: {{ Js::from(trans('core/base::notices')) }},
        };
    @endif
    </script>
    
    <style>
        body {
            opacity: 0;
        }
    </style>