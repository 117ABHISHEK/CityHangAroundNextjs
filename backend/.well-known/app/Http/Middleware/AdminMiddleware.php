<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
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
    // At this point user is ALREADY authenticated
    // auth middleware runs BEFORE this

    if (auth()->user()->user_role !== 'admin') {
        abort(403);
    }

    return $next($request);
}


}
