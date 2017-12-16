<?php

namespace App\Http\Middleware;

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
    public function handle($request, Closure $next, $qnid)
    {
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        if ($questionnaire->status == 1) {
            return response()->json([
                'status' => 0
            ]);
        }
        return $next($request);
    }
}
