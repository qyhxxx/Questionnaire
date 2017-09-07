<?php

namespace App\Http\Middleware;

use App\Helpers\login;
use Closure;

class Authentication
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
        if ($request->has('token')) {
            $token = $request->input('token');
            login::storage($token);
        }
        if (!$request->session()->has('data')) {
//            $link = $request->path();

            $link = "http://survey.twtstudio.com/";
            login::login($link);
        }
        return $next($request);
    }
}
