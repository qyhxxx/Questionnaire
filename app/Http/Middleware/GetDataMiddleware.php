<?php

namespace App\Http\Middleware;

use App\Editor;
use App\Http\Controllers\LoginController;
use App\Questionnaire;
use Closure;

class GetDataMiddleware
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
        }
        if ($questionnaire->status != 1) {
            if ($request->session()->has('data')) {
                $twt_name = $request->session()->get('data')['twt_name'];
                $hasPower = Editor::hasPower($qnid, $twt_name);
                if (!$hasPower) {
                    return response()->json([
                        'status' => 0
                    ]);
                }

            } else {
                return response()->json([
                    'status' => 0
                ]);
            }
        } else if ($questionnaire->ischecked || $questionnaire->verifiedphone) {
            if (!$request->session()->has('data')) {
                LoginController::login();
            }
        }
        return $next($request);
    }
}
