<?php

namespace App\Http\Middleware;

use App;
use Closure;

class SetLocale
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
        /** @var App\Models\User $user */
        if ($user = $request->user()) {
            if ($user->language) {
                App::setLocale($user->language);
            }
        }

        return $next($request);
    }
}
