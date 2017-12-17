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
            if ($request->session()->has('url')) {
                $url = $request->session()->pull('url');
            } else {
                $url = "https://survey.twtstudio.com/";
            }
            header("Location:".$url);
            exit;
            //return redirect()->intended();
//            return response()->json([
//                'status' => $status
//            ]);
        }
        return $next($request);
    }
}
