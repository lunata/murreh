<?php

namespace Confform\Http\Middleware;

use Closure;
use App\Models\User;
use Redirect;

class CheckPermission
{
  /**
   * Handle an incoming request
   *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission 
     * @param  string  $route_url URL for redirect if access denied
     * 
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $route_url) 
    {  
        if (!User::checkAccess($permission)) {
            return Redirect::to($route_url) 
                ->withErrors(\Lang::get('error.permission_denied'));
        }

        return $next($request);
    }
}