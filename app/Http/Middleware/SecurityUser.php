<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\User;

class SecurityUser
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
        if(!(Auth::user()->type === User::SECURITY || (Auth::user()->type === User::ROOT && env('ROOT_USER', false)))) {
            session()->flash("error_messages", ["Acción no permitida utilizando este usuario."]);
            return redirect()->route('home');
        }
        return $next($request);
    }
}
