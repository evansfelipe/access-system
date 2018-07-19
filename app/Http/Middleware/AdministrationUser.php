<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\User;

class AdministrationUser
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
        if(!(Auth::user()->type === User::ADMINISTRATION || Auth::user()->type === User::ROOT)) {
            return redirect('/');
        }
        return $next($request);
    }
}
