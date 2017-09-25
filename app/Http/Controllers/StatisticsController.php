<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
use App\Question;
use Illuminate\Http\Request;

class StatisticsController extends Controller {
    public function getChoiceQuestions($qnid) {
        $questions = Question::getChoiceQuestions($qnid);
        return response()->json([
            'questions' => $questions
        ]);
    }

    public function getOptions(Request $request) {
        $question = $request->input('question');
        $options = Option::getOptionsByQid($question['qid']);
        return response()->json([
            'options' => $options
        ]);
    }

    public function getQuestions($qnid) {
        $questions = Question::getquestions($qnid);
        return response()->json([
            'questions' => $questions
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
            $statistics[$okey] = Answer::statistics($sidArr, $qid, $okey);
        }
        return $statistics ?? null;
    }
}