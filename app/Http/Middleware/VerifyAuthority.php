<?php

namespace App\Http\Middleware;

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
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        if($twt_name == $questionnaire['twt_name']){
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
