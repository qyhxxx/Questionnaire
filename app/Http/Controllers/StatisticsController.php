<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Helpers\statistics;
use App\Option;
use App\Question;
use App\Questionnaire;
use Illuminate\Http\Request;

class StatisticsController extends Controller {
    public function init($qnid) {
        $questionnaire = Questionnaire::getQuestionnaire($qnid)->name;
        $questions = Question::getChoiceQuestions($qnid);
        return response()->json([
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ]);
    }

    public function getOptions($qid) {
        $options = Option::getOptionsByQid($qid);
        return response()->json([
            'options' => $options
        ]);
    }

    public function statistics(Request $request, $qid) {
        $data = $request->all();
        $requirements = $data['requirements'];
        $sidArr = Answer::getSidArr($requirements);
        $options = Option::getOptionsByQid($qid);
        foreach ($options as $option) {
            $okey = $option->okey;
            $option = $option->option;
            $data = Answer::statistics($sidArr, $qid, $okey);
            $count = $data['count'];
            $sum = $data['sum'];
            $proportion = $data['proportion'];
            $statistics[] = new statistics($okey, $option, $count, $sum, $proportion);
        }
        return response()->json([
            'options' => $options,
            'statistics' => $statistics ?? null
        ]);
    }
}