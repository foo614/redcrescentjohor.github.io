<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // if (! $request->user()->hasAnyRole($role)) {
        //     abort(401, 'This action is unauthorized.');
        // }
        if($request->user()->authorizeRoles(['administrator', 'member']))
        return $next($request);
    }
}
