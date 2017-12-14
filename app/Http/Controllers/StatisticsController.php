<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Helpers\statistics;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;
use Illuminate\Http\Request;

class StatisticsController extends Controller {
    public function init($qnid) {
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $questions = Question::getChoiceQuestions($qnid);
        if ($questionnaire->status == 0 || Submit::count_answers($qnid) == 0) {
            $statistics = null;
        }
        else {
            $statistics = $this->statisticsOfAllQuestions($qnid);
        }
        return response()->json([
            'questionnaire' => $questionnaire->name,
            'questions' => $questions,
            'statistics' => $statistics
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
        $data = array();
        if (empty($sidArr)) {
            $statistics = null;
            $data = null;
        }
        else {
            $statistics = array();
            foreach ($options as $option) {
                $okey = $option->okey;
                $option = $option->option;
                $data = Answer::statistics($sidArr, $qid, $okey);
                $count = $data['count'];
                $sum = $data['sum'];
                $proportion = $data['proportion'];
                $data[] = [
                    'count' => $count,
                    'sum' => $sum,
                    'proportion' => $proportion
                ];
                $statistics[] = new statistics($okey, $option, $count, $sum, $proportion);
            }
        }
        return [
            'options' => $options,
            'data' => $data,
            'statistics' => $statistics
        ];
    }

    public function statisticsOfOneQuestion(Request $request, $qid) {
        $question = Question::getQuestionByQid($qid);
        $answers = Answer::getdata($qid);
        if (count($answers) == 0) {
            $statistics = null;
        }
        else {
            $data = $request->all();
            $requirements = $data['requirements'] ?? null;
            $qnid = Question::getQuestionByQid($qid)->qnid;
            $sidArr = Answer::getSidArr($requirements, $qnid);
            $statistics = $this->statistics($sidArr, $qid);
        }
        return response()->json([
            'question' => $question->topic,
            'qtype' => $question->qtype,
            'statistics' => $statistics
        ]);
    }

    public function statisticsOfAllQuestions($qnid) {
        $sidArr = Answer::getSidArr(null, $qnid);
        $questions = Question::getquestions($qnid);
        $statistics = array();
        foreach ($questions as $question) {
            $qid = $question->qid;
            $statistics[] = $this->statistics($sidArr, $qid);
        }
        return $statistics;
    }
}
