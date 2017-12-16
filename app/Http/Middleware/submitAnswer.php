<?php

namespace App\Http\Middleware;

use App\Helpers\functions;
use App\Http\Controllers\LoginController;
use App\Questionnaire;
use App\Submit;
use Closure;

class submitAnswer
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
        $qnid = $request->route('qnid');
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $ischecked = $questionnaire->ischecked;
        if ($ischecked) {
            if (!$request->session()->has('data')) {
                LoginController::login();
            } else {
                $twt_name = $request->session()->get('data')['twt_name'];
                $ip = functions::getIp();
                if (Submit::isRepeat($qnid, $twt_name ?? null, $ip)) {
                    return response()->json([
                        'message' => '请勿重复答题'
                    ]);
                }
            }
        }
        return $next($request);
    }
}
