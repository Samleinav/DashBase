<?php

namespace Botble\Dashplugin\Supports;

use Botble\Base\Facades\BaseHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class DashSupport
{

    private bool $tableRendered = false;

    

    public function renderTable(): bool
    {
        return $this->tableRendered = true;
    }

    public function isRenderTable(): bool
    {
        return (bool) $this->tableRendered;
    }

    public function isEnableEmailVerification(): bool
    {
        return (bool) $this->getSetting('verify_customer_email', 0);
    }

    public function getSettingPrefix(): ?string
    {
        return config('plugins.dashplugin.general.prefix');
    }

    public function getConfigPrefix(): ?string
    {
        return 'plugins.dashplugin.dash.';
    }

    public function isReviewEnabled(): bool
    {
        return (bool) setting('dash_enable_review_room', 1);
    }

    public function isApiEnabled(): bool
    {
        return (bool) setting('dash_enable_api', true);
    }


    public function getSetting(string $key, bool|int|string|null $default = ''): array|int|string|null
    {
        return setting($this->getSettingPrefix() . $key, $default);
    }

    public function loadCountriesStatesCitiesFromPluginLocation(): bool
    {
        if (! is_plugin_active('location')) {
            return false;
        }

        return (bool) $this->getSetting('load_countries_states_cities_from_location_plugin', 0);
    }

    public function viewPath(string $view): string
    {
        $themeView = Theme::getThemeNamespace() . '::views.' . $view;

        if (view()->exists($themeView)) {
            return $themeView;
        }

        return 'plugins/dashplugin::'  . $view;
    }


    public function getCheckoutData(string $key = null): mixed
    {
        $checkoutToken = session('checkout_token');

        if (! $checkoutToken) {
            $checkoutToken = Str::upper(Str::random(32));
        }

        $sessionData = [];
        if (session()->has($checkoutToken)) {
            $sessionData = session($checkoutToken);
        }

        if ($key) {
            return $sessionData[$key] ?? null;
        }

        return $sessionData;
    }

    public function saveCheckoutData(array $data): void
    {
        $checkoutToken = session('checkout_token');

        $sessionData = $this->getCheckoutData();

        $data = array_merge($sessionData, $data);

        session()->put($checkoutToken, $data);
    }

    public function getConfig(string $key, bool|int|string|null $default = ''): array|int|string|null
    {
        return config($this->getConfigPrefix() . $key, $default);
    }

    public function getDateFormat(): string
    {
        return setting('dash_date_format', $this->getConfig('date_format')) ?: 'd-m-Y';
    }


    public function dateFromRequest(string $date): Carbon|false
    {
        return Carbon::createFromFormat($this->getDateFormat(), $date);
    }

    function flagUrl(?string $flag, ?string $name = null, int $width = 16): string
    {
        if (! $flag) {
            return '';
        }

        $flag = apply_filters('cms_language_flag', $flag, $name);

        return BASE_LANGUAGE_FLAG_PATH . $flag . '.svg';
    }

}