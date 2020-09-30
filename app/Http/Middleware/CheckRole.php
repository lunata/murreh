<?php

namespace Confform\Http\Middleware;

use Closure;
use App\Models\User;
use Redirect;

class CheckRole
{
  /**
   * Handle an incoming request
   *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role 
     * @param  string  $route_url URL for redirect if access denied
     * 
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $route_url) 
    {  
        if (!User::hasRole($role)) {
            return Redirect::to($route_url) 
                ->withErrors(\Lang::get('error.permission_denied'));
        }

        return $next($request);
    }
}