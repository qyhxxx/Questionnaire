<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LoginController;
use Closure;

class VerifyToken
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
            LoginController::storage($token);
        }
        return $next($request);
    }
}
