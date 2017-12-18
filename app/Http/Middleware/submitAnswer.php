<?php

namespace App\Http\Middleware;

use App\Helpers\functions;
use App\Http\Controllers\LoginController;
use App\Questionnaire;
use App\Submit;
use App\Usr;
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
        $onceanswer = $questionnaire->onceanswer;
        $verifiedphone = $questionnaire->verifiedphone;
        $ip = functions::getIp();
        if ($onceanswer) {
            if ($request->session()->has('data')) {
                $twt_name = $request->session()->get('data')['twt_name'];
            }
            if (Submit::isRepeat($qnid, $twt_name ?? null, $ip)) {
                return response()->json([
                    'message' => '请勿重复答题'
                ]);
            }
        }
        if ($verifiedphone) {
            if (!$request->session()->has('data')) {
                LoginController::login();
            }
            $twt_name = $request->session()->get('data')['twt_name'];
            $phone = $request->session()->get('data')['phone'];
            $usr = Usr::getUsr($twt_name);
            if ($usr->phone != null || $usr->phone != $phone) {
                Usr::updateUsr($twt_name, ['phone' => $phone]);
            }
            if (Submit::isRepeat($qnid, $twt_name, $ip, $phone)) {
                return response()->json([
                    'message' => '请勿重复答题'
                ]);
            }
        }
        return $next($request);
    }
}
