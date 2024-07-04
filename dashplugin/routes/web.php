<?php

use Illuminate\Support\Facades\Route;
use Botble\Base\Facades\AdminHelper;
use Botble\Dashplugin\Facades\DashHelper;
use Botble\Theme\Facades\Theme;

use Botble\Base\Facades\BaseHelper;
use Botble\Dashplugin\Http\Controllers\InvoiceController;
use Botble\Dashplugin\Http\Controllers\Settings\CurrencySettingController;
use Botble\Dashplugin\Http\Controllers\Settings\GeneralSettingController;
use Botble\Dashplugin\Http\Controllers\Settings\InvoiceSettingController;
use Botble\Dashplugin\Http\Controllers\Settings\InvoiceTemplateSettingController;

Route::group(['namespace' => 'Botble\Dashplugin\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'dashplugins', 'as' => 'dashplugin.'], function () {
            Route::resource('', 'DashpluginController')->parameters(['' => 'dashplugin']);
        });
    });
});


Route::group(['namespace' => 'Botble\Dashplugin\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/dash', 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'settings', 'as' => 'dash.settings.', 'permission' => 'dash.settings'], function () {
            Route::get('general', [GeneralSettingController::class, 'edit'])->name('general');
            Route::put('general', [GeneralSettingController::class, 'update'])->name('general.update');

            Route::get('currencies', [CurrencySettingController::class, 'edit'])->name('currencies');
            Route::put('currencies', [CurrencySettingController::class, 'update'])->name('currencies.update');

            Route::get('invoice', [InvoiceSettingController::class, 'edit'])->name('invoice');
            Route::put('invoice', [InvoiceSettingController::class, 'update'])->name('invoice.update');

            Route::get('invoice-template', [InvoiceTemplateSettingController::class, 'edit'])->name('invoice-template');
            Route::put('invoice-template', [InvoiceTemplateSettingController::class, 'update'])->name('invoice-template.update');

            Route::post('invoice-template/reset', [
                'as' => 'invoice-template.reset',
                'uses' => 'Settings\InvoiceTemplateSettingController@reset',
                'permission' => 'invoice.template',
                'middleware' => 'preventDemo',
            ]);

            Route::get('invoice-template/preview', [
                'as' => 'invoice-template.preview',
                'uses' => 'Settings\InvoiceTemplateSettingController@preview',
                'permission' => 'invoice.template',
            ]);
        });



        Route::group(['prefix' => 'customers', 'as' => 'customer.'], function () {
            Route::resource('', 'CustomerController')->parameters(['' => 'customer']);
        });

        Route::group(['prefix' => 'features', 'as' => 'feature.'], function () {
            Route::resource('', 'FeatureController')->parameters(['' => 'feature']);
        });

        Route::group(['prefix' => 'services', 'as' => 'service.'], function () {
            Route::resource('', 'ServiceController')->parameters(['' => 'service']);
        });

        Route::group(['prefix' => 'taxes', 'as' => 'tax.'], function () {
            Route::resource('', 'TaxController')->parameters(['' => 'tax']);
        });

        Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function () {
            Route::resource('', 'InvoiceController')->parameters(['' => 'invoice']);
            Route::get('{invoice}', [InvoiceController::class, 'show'])
                ->name('show')
                ->wherePrimaryKey();
            Route::get('{invoice}/generate-invoice', 'InvoiceController@getGenerateInvoice')
                ->name('generate')
                ->wherePrimaryKey();
        });


        Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function () {
            Route::resource('', 'InvoiceController')->parameters(['' => 'invoice']);
            Route::get('{id}', [InvoiceController::class, 'show'])
                ->name('show')
                ->wherePrimaryKey();
            Route::get('{id}/generate-invoice', 'InvoiceController@getGenerateInvoice')
                ->name('generate')
                ->wherePrimaryKey();
        });

    });

});
/**
 * Routes for customers
 */

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Theme::registerRoutes(function () {
        Route::group([
            'namespace' => 'Botble\Dashplugin\Http\Controllers\Front\Customers',
            'middleware' => ['web', 'core', 'customer.guest'],
            'as' => 'customer.',
        ], function () {
            Route::get('login', 'LoginController@showLoginForm')->name('login');
            Route::post('login', 'LoginController@login')->name('login.post');

            Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register')->name('register.post');

            Route::get(
                'password/request',
                'ForgotPasswordController@showLinkRequestForm'
            )->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset.update');
            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        });

        Route::group([
            'namespace' => 'Botble\Dashplugin\Http\Controllers\Front\Customers',
            'middleware' => [
                'web',
                'core',
                DashHelper::isEnableEmailVerification() ? 'customer' : 'customer.guest',
            ],
            'as' => 'customer.',
        ], function () {
            Route::get('register/confirm/resend', 'RegisterController@resendConfirmation')
                ->name('resend_confirmation');
            Route::get('register/confirm/{user}', 'RegisterController@confirm')
                ->name('confirm');
        });

        Route::group([
            'namespace' => 'Botble\Dashplugin\Http\Controllers\Front\Customers',
            'middleware' => ['web', 'core', 'customer'],
            'as' => 'public.',
        ], function () {

                Route::get('/', [
                    'as' => 'index',
                    'uses' => 'PublicController@getIndex',
                ]);
            });

            
            Route::group([
                'namespace' => 'Botble\Dashplugin\Http\Controllers',
                'middleware' => ['web', 'core', 'customer'],
                'prefix' => 'table',
                'as' => 'table.',
            ], function () {
                    Route::resource('customers', 'CustomerController')->parameters(['' => 'customer']);

                    

                    require core_path('table/routes/web-actions.php');
                });

        Route::group([
            'namespace' => 'Botble\Dashplugin\Http\Controllers\Front\Customers',
            'middleware' => ['web', 'core', 'customer'],
            'prefix' => 'customer',
            'as' => 'customer.',
        ], function () {
            Route::get('logout', 'LoginController@logout')->name('logout');

            Route::get('profile', [
                'as' => 'profile',
                'uses' => 'PublicController@getIndex',
            ]);

            Route::get('settings', [
                'as' => 'settings',
                'uses' => 'PublicController@getIndex',
            ]);

            Route::get('edit-account', [
                'as' => 'edit-account',
                'uses' => 'PublicController@getEditAccount',
            ]);

            Route::get('edit-account', [
                'as' => 'edit-account',
                'uses' => 'PublicController@getEditAccount',
            ]);

            Route::post('edit-account', [
                'as' => 'edit-account.post',
                'uses' => 'PublicController@postEditAccount',
            ]);

            Route::get('change-password', [
                'as' => 'change-password',
                'uses' => 'PublicController@getChangePassword',
            ]);

            Route::post('change-password', [
                'as' => 'post.change-password',
                'uses' => 'PublicController@postChangePassword',
            ]);

            Route::post('avatar', [
                'as' => 'avatar',
                'uses' => 'PublicController@postAvatar',
            ]);
/** 
            Route::get('licenses', [
                'as' => 'bookings',
                'uses' => 'licenseController@index',
            ]);

            Route::get('licenses/{id}', [
                'as' => 'bookings.show',
                'uses' => 'licenseController@show',
            ]);

            Route::get('generate-invoice/{id}', [
                'as' => 'generate-invoice',
                'uses' => 'licenseController@getGenerateInvoice',
            ])->wherePrimaryKey();
*/
        });
    });
}