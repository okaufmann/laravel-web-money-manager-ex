<?php

namespace App\Http\Middleware;

use App\Constants;
use Closure;

class MmexEnsureGuid
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
        $data = $request->all();
        if(isset($data["guid"]) && $data["guid"] == "{D6A33C24-DE43-D62C-A609-EF5138F33F27}") {
            return $next($request);
        }

        return response(Constants::$wrong_guid);
    }
}
