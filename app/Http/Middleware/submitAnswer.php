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
        if ($questionnaire->recovery_at != null && time() > $questionnaire->recovery_at) {
            Questionnaire::updateByQnid($qnid, ['status' => 2]);
            return response()->json([
                'status' => 0
            ]);
        }
        $ischecked = $questionnaire->ischecked;
        if ($ischecked && !$request->session()->has('data')) {
            LoginController::login();
        }
        $onceanswer = $questionnaire->onceanswer;
        $verifiedphone = $questionnaire->verifiedphone;
        if ($onceanswer) {
            $twt_name = $request->session()->get('data')['twt_name'];
            if (Submit::isRepeat($qnid, $twt_name)) {
                return response()->json([
                    'message' => '请勿重复答题'
                ]);
            }
        }
        if ($verifiedphone) {
            $twt_name = $request->session()->get('data')['twt_name'];
            $phone = $request->session()->get('data')['phone'];
            $usr = Usr::getUsr($twt_name);
            if ($usr->phone != null || $usr->phone != $phone) {
                Usr::updateUsr($twt_name, ['phone' => $phone]);
            }
            if (Submit::isRepeat($qnid, $twt_name, $phone)) {
                return response()->json([
                    'message' => '请勿重复答题'
                ]);
            }
        }
        return $next($request);
    }
}
