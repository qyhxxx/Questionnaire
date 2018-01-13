<?php

namespace App\Http\Middleware;

use App\Editor;
use App\Questionnaire;
use Closure;

class Update
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
        if ($questionnaire->status == 0) {
            if ($request->session()->has('data')) {
                $twt_name = $request->session()->get('data')['twt_name'];
                $hasPower = Editor::hasPower($qnid, $twt_name);
                if (!$hasPower) {
                    return response()->json([
                        'status' => 0
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 0
            ]);
        }
        return $next($request);
    }
}
