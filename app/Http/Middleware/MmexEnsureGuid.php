<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;
use App\Services\Mmex\MmexConstants;

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
        $guid = $request->input('guid');
        $user = User::where('mmex_guid', $guid)->first();

        if (! empty($guid) && $user && $guid == $user->mmex_guid) {
            // login user on api guard (simple alternative login method)
            Auth::guard('api')->setUser($user);

            return $next($request);
        }

        return response(MmexConstants::$wrong_guid)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
