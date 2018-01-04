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
//        if ($questionnaire->recovery_at != null && time() > $questionnaire->recovery_at) {
//            Questionnaire::updateByQnid($qnid, ['status' => 2]);
//        }
        $onceanswer = $questionnaire->onceanswer;
        $verifiedphone = $questionnaire->verifiedphone;
        $ip = $request->getClientIp();
        if ($onceanswer) {
            if ($request->session()->has('data')) {
                $twt_name = $request->session()->get('data')['twt_name'];
            }
            if (Submit::isRepeat($qnid, $twt_name ?? null)) {
                return response()->json([
                    'info' => 0
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
            if (Submit::isRepeat($qnid, $twt_name, $phone)) {
                return response()->json([
                    'info' => 0
                ]);
            }
        }
        return $next($request);
    }
}
