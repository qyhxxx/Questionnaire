<?php

namespace App\Http\Middleware;

use app\Http\Controllers\Admin\LoginController;
use Closure;

class VerifyAdminToken
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
