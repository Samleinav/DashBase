<?php

namespace Botble\Dashplugin\Supports;

use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Botble\Base\Supports\Breadcrumb;
use Botble\Dashplugin\Facades\BreadcrumbFront;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Exception;
use PhpParser\Node\Stmt\Continue_;

class DashSupport
{

    private bool $tableRendered = false;
    private array $customMenu = [];
    protected string $breadcrumbGroup = 'front';

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
    public function loadConfig(){
        $this->loadMenuConfig();
    }

    private function loadMenuConfig(){
        $menu = Theme::getConfig('events.menuFront');
        if(is_callable($menu)){
            $this->customMenu = $menu();
        }else{
            return ;
        }
    }

    private function menuBuilder(array|string $menu = [], bool $init = false): array
    {
        $menuNodes = [];

        if(is_string($menu)){
            $menu = Theme::getConfig('events.' . $menu);
            
            if(is_callable($menu)){
                $menu = $menu();
            }else{
                return [];
            }
        }
       

        $customer = Auth::guard('customer')->user();
        
        foreach ($menu as $title => $value) {
            if (is_string($title) && str_starts_with($title, '@')) {

                $partial = str_replace('@', '', $value['ref']);
                
                $menuNodes[] = [
                    "render" => $this->menuPartial($partial, $value),
                ];

                continue;
            }

            $hasChild = isset($value['child']) && is_array($value['child']) ? true : false;

            $url = is_array($value) && isset($value['url']) ? $value['url'] : $value;
        
            if (! $hasChild && str_contains($url, '.') && filter_var($url, FILTER_VALIDATE_URL) == false) {

                $route = Route::getRoutes()->getByName($url);
              
                if (! $route) {
                    //make error
                    throw new Exception('Route not found: ' . $url);
                }
                    // Verificar si la ruta tiene el atributo 'permission'
                    $permissions = $route->getAction('permission');

                if(is_array($permissions)) {
                    $_permissions = [];
                    foreach ($permissions as $permission) {
            
                        if (str_ends_with($permission, '.')) {
                            
                            // Si el permiso termina con '.', concatenar con la acción de la ruta
                            $action = last(explode('.', $route->getName()));
                            $permission .= $action;
                            
                        }
                        $_permissions[] = $permission;
                    }

                    $permissions = $_permissions;

                }elseif(is_string($permissions) && str_ends_with($permissions, '.')){
                    $action = last(explode('.', $route->getName()));
                    $permissions .= $action;
                }

                if( $permissions && $customer ){
                    if ( !$customer->hasPermission($permissions)) {
                        continue;
                    }
                }
                    
                $url = url($route->uri());
            } 
      
            $node = [
                'url' => $url,
                'icon_font' => is_array($value) && isset($value['icon']) ? $value['icon'] : '',
                'title' => is_array($value) && isset($value['title']) ? $value['title'] : $title,
                'css_class' => is_array($value) && isset($value['css']) ? $value['css'] : '',
                'active' => false,
                'target' => '_self',
                'has_child' => $hasChild ? 1 : 0,
                'child' => $hasChild ? $this->menuBuilder($value['child']) : [],
            ];
    
            $menuNodes[] = $node;
        }

        return $menuNodes;
    }

    public function canUserAccess(string $rute, $user): bool{

        $route = Route::getRoutes()->getByName($rute);
              
        if (! $route) {
            //make error
            throw new Exception('Route not found: ' . $rute);
        }

        // Verificar si la ruta tiene el atributo 'permission'
        $permissions = $route->getAction('permission');

        if(is_array($permissions)) {
            $_permissions = [];
            foreach ($permissions as $permission) {
                
                if (str_ends_with($permission, '.')) {
                    
                    // Si el permiso termina con '.', concatenar con la acción de la ruta
                    $action = last(explode('.', $route->getName()));
                    $permission .= $action;
                    
                }
                $_permissions[] = $permission;
            }

            $permissions = $_permissions;

        }elseif(is_string($permissions) && str_ends_with($permissions, '.')){
            $action = last(explode('.', $route->getName()));
            $permissions .= $action;
        }

        if( $permissions ){
            return $user->hasPermission($permissions);
        }

        return true;

    }

    public function partial(string $partial, array $args = []): string
    {
        return Theme::partial($partial, $args);
    }

    public function menuPartial(string $partial, array $data = []): string
    {
        return Theme::partial("menu.{$partial}", $data);
    }

    public function menu(array|string $menu = [], bool $init = false): array{
       return $this->menuBuilder($menu, true);
    }

    function getMenu(string $menu = '', bool $init = false): array
    {
        return $this->menu($menu, $init);
    }

    /**
     * breadcrumb
     */
    public function breadcrumb(): Breadcrumb
    {
        $breadcrumb = BreadcrumbFront::for($this->breadcrumbGroup);

        if ($this->breadcrumbGroup === 'front') {
            $breadcrumb->add(trans('Home'), route('public.index'));
        }

        return $breadcrumb;
    }

}