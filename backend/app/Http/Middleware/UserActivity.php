<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Cache;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivity
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
        if (Auth::check()) {
            $user = Auth::user();
            $now = Carbon::now();

            User::whereKey($user->id)->update([
                'lastactive' => $now,
            ]);

            $expiresAt = $now->copy()->addMinutes(5);
            Cache::put('user-is-online-' . $user->id, true, $expiresAt);
        }

        return $next($request);
    }
}
