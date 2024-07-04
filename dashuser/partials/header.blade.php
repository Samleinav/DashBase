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
    
    <script src="{{ asset('vendor/core/core/base/libraries/jquery.min.js?v=1.5.4') }}"></script>
    {!! Theme::header() !!}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    
    @if(DashHelper::isRenderTable())
        {!! Assets::renderHeader() !!}
            <script>
                window.siteUrl = "{{ BaseHelper::getHomepageUrl() }}";
            </script>

            <script type="text/javascript">
                'use strict';
                window.trans = Object.assign(window.trans || {}, JSON.parse('{!! addslashes(json_encode(trans('plugins/marketplace::marketplace'))) !!}'));

                var BotbleVariables = BotbleVariables || {};
                BotbleVariables.languages = {
                    tables: {!! json_encode(trans('core/base::tables'), JSON_HEX_APOS) !!},
                    notices_msg: {!! json_encode(trans('core/base::notices'), JSON_HEX_APOS) !!},
                    pagination: {!! json_encode(trans('pagination'), JSON_HEX_APOS) !!},
                    system: {
                        character_remain: '{{ trans('core/base::forms.character_remain') }}'
                    }
                };

                
            </script>
    @endif
    
    <style>
        body {
            opacity: 0;
        }
    </style>