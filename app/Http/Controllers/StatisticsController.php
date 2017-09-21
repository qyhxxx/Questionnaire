<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller {
    public function getStems($qnid) {
        $quesions = Question::getChoiceQuestions($qnid);
        return response()->json([
            'questions' => $quesions
        ]);
    }

    public function getOptions(Request $request) {
        $question = $request->input('question');
        if ($question['qtype'] == 5) {
            $options = [
                'stype' => $question['stype'],
                'srange' => $question['srang'],
                'min' => $question['min'],
                'max' => $question['max']
            ];
        }
        else {
            $options = Option::getOptionsByQid($question['qid']);
        }
        return response()->json([
            'options' => $options
        ]);
    }

    public function statistics(Request $request) {
        $data = $request->all();
        $selects = $data['selects'];
        $showQid = $data['showQid'];
        $sidArr = Answer::getSidArr($selects);
        $question = Question::getQuestionByQid($showQid);
        $qid = $question->qid;
        $options = Option::getOptionsByQid($qid);
        foreach ($options as $option) {
            $okey = $option->okey;
            $statistics[$okey] = 0;
            $statistics[$okey] += Answer::statistics($sidArr, $qid, $okey);
        }
        return $statistics ?? null;
    }
}