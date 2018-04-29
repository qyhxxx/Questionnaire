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
        if ($request->has('token') && strlen($request->get('token')) > 45) {
            $token = $request->input('token');
            $status = LoginController::storage($token);
            if ($status) {
                $url = urldecode($request->get('from'));
                header("Location:".$url);
                exit;
            } else {
                return response()->json([
                    'message' => '登录失败'
                ]);
            }
        }
        return $next($request);
    }
}
