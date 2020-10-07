<?php

namespace App\Http\Middleware;
use Session;
use Closure;

class RedirectIfUserAuthenticated
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
        if(auth()->guard('user')->check())
        {
            Session::flash('error-msg', 'You are already logged in');
            return redirect()->route('user-home');
        }
        return $next($request);
    }
}
