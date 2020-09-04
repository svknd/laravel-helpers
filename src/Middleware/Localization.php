<?php

namespace Svknd\Laravel\Helpers\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // set if client sent X-locatiozation
        if ($request->hasHeader('X-Localization')) {
            app()->setLocale($request->header('X-Localization'));
        }
        return $next($request);
    }
}
