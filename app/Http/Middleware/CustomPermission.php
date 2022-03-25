<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiName = $request->route()->getName();
        $user = $request->user();
        if ($user->tokenCan($apiName)) {
            return $next($request);
        } else {
            return \response('Unauthorized', 401);
        }
    }
}
