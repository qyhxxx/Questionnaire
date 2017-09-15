<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller {
    public function getStems($qnid) {
        $stems = Question::getStemsOfChoiceQuestions($qnid);
        return response()->json([
            'stems' => $stems ?? null
        ]);
    }

    public function getOptions(Request $request) {
        $qid = $request->input('qid');
        $question = Question::getQuestionByQid($qid);
        $qtype = $question->qtype;
        if ($qtype == 5) {
            $options = [
                'stype' => $question->stype,
                'srange' => $question->srange,
                'min' => $question->min,
                'max' => $question->max
            ];
        }
        else {
            $options = Option::getOptionsByQid($qid);
        }
        return response()->json([
            'options' => $options
        ]);
    }

    public function statistics(Request $request) {
        $data = $request->all();
        $selects = $data['selects'];
        $showQid = $data['showQid'];
        $submits = Answer::getSubmits($selects);
        $question = Question::getQuestionByQid($showQid);
        $qid = $question->qid;
        $options = Option::getOptionsByQid($qid);
        foreach ($options as $option) {
            $okey = $option->okey;
            $statistics[$okey] = 0;
            foreach ($submits as $submit) {
                $sid = $submit->sid;
                $statistics[$okey] += Answer::statistics($sid, $okey);
            }
        }
        return $statistics ?? null;
    }
}