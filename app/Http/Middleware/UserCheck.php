<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class UserCheck
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
        if(!auth()->guard('user')->check())
        {
            Session::flash('error-msg', 'Please Login First');
            return redirect()->route('user-login');
        }
        
        return $next($request);
    }
}
