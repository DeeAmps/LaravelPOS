<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$myroles)
    {
        if($request->user() === null) {
            return redirect()->route('login');
        }
        $roles = isset($myroles) ? $myroles : null;
        if($request->user()->hasAnyRole($roles) || !$roles) {
            return $next($request);
        }
        return response('insufficeient permission', 401);   
        //return redirect()->back();    
    }
}
