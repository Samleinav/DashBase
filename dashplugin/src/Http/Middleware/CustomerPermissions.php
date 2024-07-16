<?php

namespace Botble\Dashplugin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPermissions
{
    public function handle(Request $request, Closure $next, string $guard = 'customer')
    {
        $route = $request->route();
       
        // Verificar si la ruta tiene el atributo 'permission'
        $permissions = $route->getAction('permission');

        if(is_array($permissions)) {
            $_permissions = [];
            foreach ($permissions as $permission) {
                
                if (str_ends_with($permission, '.')) {
                    
                    // Si el permiso termina con '.', concatenar con la acción de la ruta
                    $action = last(explode('.', $route->getName()));
                    //if update or create 
                    if(in_array($action, ['update', 'store'])) {
                        $action = explode('.', $route->getName());
                        $action = $action[count($action)-2];
                    }
                    $permission .= $action;
                    
                }
                $_permissions[] = $permission;
            }

            $permissions = $_permissions;

        }elseif(is_string($permissions) && str_ends_with($permissions, '.')){
            $action = last(explode('.', $route->getName()));
            $permissions .= $action;
        }
        
        if ($permissions) {
            $customer = Auth::guard($guard)->user();
            if (!$customer || !$customer->hasPermission($permissions)) {
                // Redirigir o devolver una respuesta de error
                return redirect(route('public.index'));
            }
        }

        return $next($request);
    }
}
