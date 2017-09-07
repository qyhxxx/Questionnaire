<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller {
    public function add(Request $request) {
        if ($request->isMethod('POST')) {
            $data_questionnaire = $request->input('questionnaire');
            $data_questionnaire = (array)$data_questionnaire;
            dd($data_questionnaire);
//            $data_questionnaire['user_number'] = $request->session()->get('data')['user_number'];
//            $questionnaire = Questionnaire::add($data_questionnaire);
//            $qnid = $questionnaire->qnid;
//            return redirect('edit/qnid/' . $qnid);
        }
        return view('Questionnaire.add');
    }

    public function addQuestion(Request $request, $qnid) {
        if ($request->isMethod('POST')) {
            $data_questionnaire = $request->input('questionnaire');
            $data_question = $request->input('question');
            $data_option = $request->input('option');
            $data_questionnaire['qcount'] = Questionnaire::getCount($qnid) + 1;
            $questionnaire = Questionnaire::updateByQnid($qnid, $data_questionnaire);
            $data_question['qnid'] = $qnid;
            $data_question['qnum'] = $questionnaire->qcount;
            $question = Question::add($data_question);
            $qtype = $data_question['qtype'];
            if ($qtype != 3 && $qtype != 4 && $qtype != 5) {
                $qid = $question->qid;
                $data_problem = $request->input('problem') ?? null;
                Option::add($data_option, $data_problem, $qid, $qtype);
            }
            $questions = Question::getAllQuestions($qnid);
            return response()->json([
                'questionnaire' => $questionnaire,
                'questions' => $questions,
                'question' => new Question()
            ]);
        }
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        return view('Question.add', [
            'questionnaire' => $questionnaire,
            'question' => new Question()
        ]);
    }

    public function submit(Request $request, $qnid) {
        if ($request->isMethod('POST')) {
            if ($request->session()->has('data')) {
                $user_number = $request->session()->get('data')['user_number'];
            }
            $ip = $request->getClientIp();
            $data = [
                'qnid' => $qnid,
                'user_number' => $user_number ?? null,
                'ip' => $ip ?? null
            ];
            $submit = Submit::add($data);
            $data = $request->all();
            $answer = Answer::add($data, $qnid);
            return response()->json([
                'answer' => $answer
            ]);
        }
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $questions = Question::getAllQuestions($qnid);
        return response()->json([
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ]);
    }
}