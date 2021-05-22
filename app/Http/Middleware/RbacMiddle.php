<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RbacMiddle
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
        //firstly run request to get user data
        $response = $next($request);
        
        //if has Rbac admin role
        if(!Auth::user()->hasRole('admin')){ 
            throw new \App\Exceptions\myException('You have No rbac rights to Admin Panel');
		}
        //return $next($request);
        return $response;
    }
}
