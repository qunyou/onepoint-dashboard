<?php

namespace Onepoint\Dashboard\Middleware;

use Closure;

class AuthenticateGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, $route_name = 'home')
    {
        if (!auth()->guard($guard)->check()) {
            return redirect()->route($route_name);
        }
        return $next($request);
    }
}
