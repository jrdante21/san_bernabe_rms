<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $level=3)
    {
        $user1 = Auth::guard('admin')->check();
        $user2 = Auth::guard('official')->check();

        if ($user1 || $user2) {
            $guard = ($user1) ? 'admin' : 'official';
            $user = Auth::guard($guard)->user();

            if ($user->active >= 1) {
                return ($user->level <= $level) ? $next($request) : response('Authorization not allowed.', 401);

            } else {
                return response('This account is deactivated.', 401);
            }
            
        } else {
            return response('Login first. Reload this page to login', 401);
        }
        

    }
}
