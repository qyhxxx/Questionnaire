<?php

namespace App\Http\Middleware;

use App\Editor;
use App\Http\Controllers\LoginController;
use Closure;
use App\Questionnaire;

class VerifyAuthority
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
        $qnid = $request->route('id');
        $twt_name = $request->session()->get('data')['twt_name'];
        $questionnaire = Editor::hasPower($qnid, $twt_name);
        if($questionnaire){
            return $next($request);
        }
//        elseif(!$request->session()->has('data')){
//            LoginController::login();
//        }
        else{
            return response()->json([
                'message' => '您好像没有权限哦',
            ]);
        }

    }
}
