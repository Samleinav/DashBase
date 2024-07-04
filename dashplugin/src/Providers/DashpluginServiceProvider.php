<?php

namespace Botble\Dashplugin\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Facades\EmailHandler;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Base\Facades\DashboardMenu;
use Illuminate\Foundation\AliasLoader;
use Botble\Dashplugin\Facades\DashHelper; 
use Botble\Dashplugin\Forms\Fronts\Auth\ForgotPasswordForm;
use Botble\Dashplugin\Forms\Fronts\Auth\LoginForm;
use Botble\Dashplugin\Forms\Fronts\Auth\RegisterForm;
use Botble\Dashplugin\Forms\Fronts\Auth\ResetPasswordForm;
use Botble\Dashplugin\Http\Middleware\RedirectIfCustomer;
use Botble\Dashplugin\Http\Middleware\RedirectIfNotCustomer;
use Botble\Dashplugin\Http\Requests\Fronts\Auth\ForgotPasswordRequest;
use Botble\Dashplugin\Http\Requests\Fronts\Auth\LoginRequest;
use Botble\Dashplugin\Http\Requests\Fronts\Auth\RegisterRequest;
use Botble\Dashplugin\Http\Requests\Fronts\Auth\ResetPasswordRequest;
use Botble\Dashplugin\Models\Customer;
use Botble\Dashplugin\Repositories\Eloquent\CustomerRepository;
use Botble\Dashplugin\Repositories\Interfaces\CustomerInterface;
use Botble\Captcha\Facades\Captcha;
use Botble\Theme\Facades\SiteMapManager;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Dashplugin\Models\Service;

class DashpluginServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        config([
            'auth.guards.customer' => [
                'driver' => 'session',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model' => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table' => 'dash_customer_password_resets',
                'expire' => 60,
            ],
        ]);
        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        //Middleware for Frontend role customer
        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
        $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);

        $aliasLoader = AliasLoader::getInstance();

        if (! class_exists('DashHelper')) {
            $aliasLoader->alias('DashHelper', DashHelper::class);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            
            LanguageAdvancedManager::registerModule(Service::class, [
                'name',
                'description',
                'content',
            ]);
        }

        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerRepository(new Customer());
        });
    }
    
    public function boot(): void
    {
        $this
            ->setNamespace('plugins/dashplugin')
            ->loadHelpers()
            ->loadAndPublishConfigurations(["permissions","dash"])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadMigrations();
            
            DashboardMenu::default()->beforeRetrieving(function () {
                DashboardMenu::registerItem([
                    'id' => 'cms-plugins-dashplugin',
                    'priority' => 1,
                    'parent_id' => null,
                    'name' => 'Dash-app',
                    'icon' => 'ti ti-building-skyscraper',
                    'route' => 'dashplugin.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-service',
                    'priority' => 6,
                    'parent_id' => 'cms-plugins-dashplugin',
                    'name' => 'plugins/dashplugin::service.name',
                    'route' => 'service.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-customer',
                    'priority' => 9,
                    'parent_id' => 'cms-plugins-dashplugin',
                    'name' => 'plugins/dashplugin::customer.name',
                    'route' => 'customer.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-tax',
                    'priority' => 10,
                    'parent_id' => 'cms-plugins-dashplugin',
                    'name' => 'plugins/dashplugin::tax.name',
                    'route' => 'tax.index',
                ]);
            });

            $this->app['events']->listen(RouteMatched::class, function () {
                EmailHandler::addTemplateSettings(DASH_MODULE_SCREEN_NAME, config('plugins.dashplugin.email'));
            });

            SiteMapManager::registerKey(['licenses']);

            $this->app->register(EventServiceProvider::class);

            if (is_plugin_active('captcha')) {
                Captcha::registerFormSupport(LoginForm::class, LoginRequest::class, trans('plugins/dashplugin::dash.login_form'));
                Captcha::registerFormSupport(RegisterForm::class, RegisterRequest::class, trans('plugins/dashplugin::dash.register_form'));
                Captcha::registerFormSupport(ForgotPasswordForm::class, ForgotPasswordRequest::class, trans('plugins/dashplugin::dash.forgot_password_form'));
                Captcha::registerFormSupport(ResetPasswordForm::class, ResetPasswordRequest::class, trans('plugins/dashplugin::dash.reset_password_form'));
            }
            
    }
}
