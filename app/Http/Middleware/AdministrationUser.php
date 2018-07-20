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
            session()->flash("message_errors", ["Acción no permitida utilizando este usuario."]);
            return redirect()->route('home');
        }
        return $next($request);
    }
}
