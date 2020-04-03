<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class APIversion
 * @package App\Http\Middleware
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class APIversion
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        config(['app.api.version' => $guard]);
        return $next($request);
    }
}
