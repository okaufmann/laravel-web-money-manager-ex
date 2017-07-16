<?php

namespace App\Http\Middleware;

use Closure;

class EnsureHttps
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && config('money-manager.https')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
