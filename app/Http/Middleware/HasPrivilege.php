<?php

namespace App\Http\Middleware;

use Closure;

class HasPrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $privilege)
    {
        if (!$request->user()->hasPrivilege($privilege)) {
            return redirect('/login');
        }
        return $next($request);
    }
}
