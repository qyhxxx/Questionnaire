<?php

namespace App\Http\Middleware;

use App\Helpers\login;
use Closure;

class CheckToken
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
        return $next($request);
    }
}
