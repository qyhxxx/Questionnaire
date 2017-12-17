<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LoginController;
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
        if (!$request->session()->has('data')) {
            $url = $request->url();
            session(['url' => $url]);
            session()->save();
            LoginController::login();
        }
        return $next($request);
    }
}
