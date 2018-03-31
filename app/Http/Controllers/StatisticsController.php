<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Helpers\statistics;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Classes\Cache;

class StatisticsController extends Controller {
    public function init($qnid) {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
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
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
        if (Cache::has($qid)) {
            return Cache::get($qid);
        }
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
                $data_onequestion = Answer::statistics($sidArr, $qid, $okey);
                $count = $data_onequestion['count'];
                $sum = $data_onequestion['sum'];
                $proportion = $data_onequestion['proportion'];
                $data[] = [
                    'count' => $count,
                    'sum' => $sum,
                    'proportion' => $proportion
                ];
                $statistics[] = new statistics($okey, $option, $count, $sum, $proportion);
            }
        }
        $return = [
            'options' => $options,
            'data' => $data,
            'statistics' => $statistics
        ];
        Cache::put($qid, $return);
        return $return;
    }

    public function statisticsOfOneQuestion(Request $request, $qid) {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
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
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
        if (Cache::has($qnid)) {
            return Cache::get($qnid);
        }
        $sidArr = Answer::getSidArr(null, $qnid);
        $questions = Question::getquestions($qnid);
        $statistics = array();
        foreach ($questions as $question) {
            $qid = $question->qid;
            $statistics[] = $this->statistics($sidArr, $qid);
        }
        Cache::put($qnid, $statistics);
        return $statistics;
    }
}
