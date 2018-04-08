<?php

namespace App\Http\Middleware;

use app\Http\Controllers\Admin\LoginController;
use Closure;

class AdminAuthentication
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
            LoginController::login();
        }
        return $next($request);
    }
}
