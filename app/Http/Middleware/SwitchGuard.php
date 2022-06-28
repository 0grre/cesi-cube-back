<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SwitchGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        if (in_array($guard, array_keys(config("auth.guards")))) {
            config(["auth.defaults.guard" => $guard]);
        }

        return $next($request);
    }
}
