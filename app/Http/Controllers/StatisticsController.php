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
            'questions' => $questions,
            'statistics' => $this->statisticsOfAllQuestions($qnid)
        ]);
    }

    public function getOptions($qid) {
        $options = Option::getOptionsByQid($qid);
        return response()->json([
            'options' => $options
        ]);
    }

    public function statistics($sidArr, $qid) {
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
        return [
            'options' => $options,
            'statistics' => $statistics ?? null
        ];
    }

    public function statisticsOfOneQuestion(Request $request, $qid) {
        $data = $request->all();
        $requirements = $data['requirements'];
        $sidArr = Answer::getSidArr($requirements);
        $statistics = $this->statistics($sidArr, $qid);
        return response()->json($statistics);
    }

    public function statisticsOfAllQuestions($qnid) {
        $sidArr = Answer::getSidArr();
        $questions = Question::getquestions($qnid);
        foreach ($questions as $question) {
            $qid = $question->qid;
            $statistics[] = $this->statistics($sidArr, $qid);
        }
        return $statistic ?? null;
    }
}