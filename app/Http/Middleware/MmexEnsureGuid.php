<?php

namespace App\Http\Middleware;

use App\Constants;
use Closure;

class MmexEnsureGuid
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
        $data = $request->all();
        if (isset($data['guid']) && $data['guid'] == config('services.mmex.guid')) {
            return $next($request);
        }

        return response(Constants::$wrong_guid)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
