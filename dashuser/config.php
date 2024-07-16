<?php

use Botble\Theme\Theme;
use Botble\Base\Facades\Assets;
use Botble\Theme\Asset;
use Botble\Dashplugin\Facades\DashHelper;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these events can be overridden by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function ($theme): void {
            // You can remove this line anytime.
           

        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme): void {
            // Partial composer.
            // $theme->partialComposer('header', function($view) {
            //     $view->with('auth', \Auth::user());
            // });
            Assets::removeScripts([
                'core-ui',
                'excanvas',
                'ie8-fix',
                'modernizr',
                'select2',
                'datepicker',
                'cookie',
                'core',
                'app',
                'toastr',
                'custom-scrollbar',
                'stickytableheaders',
                'jquery-waypoints',
                'spectrum',
                'fancybox',
                'fslightbox',
            ]);

            Assets::removeStyles([
                'fontawesome',
                'select2',
                'toastr',
                'custom-scrollbar',
                'datepicker',
                'spectrum',
                'fancybox',
            ]);
            // You may use this event to set up your assets.
            /**HEADER */
            $theme->asset()->usePath()->add('style', 'css/light.css');
            $theme->asset()->container('header')->add('jquery', '/vendor/core/core/base/libraries/jquery.min.js');
            $theme->asset()->container('header')->add('bootstrapbundle', 'vendor\core\core\base\libraries\bootstrap.bundle.min.js', ['jquery']);
            

            /**FOOTER */
            Assets::addScripts(['cookie', 'app', 'toastr', 'custom-scrollbar', 'datepicker', 'spectrum', 'fancybox', 'fslightbox', 'jquery-waypoints', 'core']);
            Assets::addStyles(['fontawesome', 'select2', 'toastr', 'custom-scrollbar', 'datepicker', 'spectrum', 'fancybox']);
            $theme->asset()->container('footer')->usePath()->add('scripts', 'js/main.js', ['jquery']);
            $theme->asset()->container('footer')->usePath()->add('settings', 'js/settings.js', ['jquery']);
            

            if (function_exists('shortcode')) {
                $theme->composer(['page', 'post'], function (\Botble\Shortcode\View\View $view) {
                    $view->withShortcodes();
                });
            }
        },

        'menuFront' => function() {
            return [
            "Home" => "public.index",
            "With Children" => [
                'child'=>[
                    "With Children 1" => "/with-children-1",
                    "With Children 2" => [
                        "url" => "public.user.profile",
                    ]
                ]
            ],
            "@separator1" => [
                'ref' => 'separator-with-title',
                "title" => "Title Separator",
            ],
            "with rute" => 'public.settings.roles.index',
            "with icon" => [
                "icon" => "fa fa-home",
                "title" => "with icon",
                "url" => "/with-icon",
            ],
            ] ;
        },

        'menuSettings' => function() {
            return [
                '@separator1' => [
                    'ref' => 'settings-separator',
                    'title' => 'Settings',
                ],
                'Overview' => 'public.settings.index',
                'Users' => 'public.settings.customers.index',
                'Roles & Permissions' => 'public.settings.roles.index',
            ];
        },

        'menuProfile' => function() {
            return [
                '@separator1' => [
                    'ref' => 'settings-separator',
                    'title' => 'Profile',
                ],
                'My account' => 'public.user.profile',
                'My Notifications' => 'public.user.notifications',
                
            ];
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [
            'default' => function ($theme): void {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            },
        ],
    ],
];
