<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user1 = Auth::guard('admin')->check();
        $user2 = Auth::guard('official')->check();

        if ($user1 || $user2) {
            $guard = ($user1) ? 'admin' : 'official';
            $user = Auth::guard($guard)->user();

            if ($user->active >= 1) {
                return $next($request);

            } else {
                Session::flush();
                Auth::guard($guard)->logout();
                return redirect()->route('login');
            }

        } else {
            return redirect()->route('login');
        }
        
    }
}
