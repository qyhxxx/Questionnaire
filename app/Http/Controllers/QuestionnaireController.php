<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Helpers\functions;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller {
    public function add(Request $request) {
        if ($request->isMethod('POST')) {
            $data_questionnaire = $request->input('questionnaire');
//            $data_questionnaire = functions::objectToArr($data_questionnaire);
            $data_questionnaire['user_number'] = $request->session()->get('data')['user_number'];
            $questionnaire = Questionnaire::add($data_questionnaire);
            $qnid = $questionnaire->qnid;
            return redirect('edit/qnid/' . $qnid);
//            return response()->json([
//                'qnid' => $qnid
//            ]);
        }
        return view('Questionnaire.add');
    }

    public function addQuestion(Request $request, $qnid) {
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $data_questionnaire = functions::objectToArr($data['questionnaire']);
            $questions = $data['questions'];
            $qcount = count($questions);
            $data_questionnaire['user_number'] = $request->session()->get('data')['user_number'];
            $data_questionnaire['qcount'] = $qcount;
            $questionnaire = Questionnaire::updateByQnid($qnid, $data_questionnaire);
            for ($i = 0; $i < $qcount; $i++) {
                $data_question = functions::objectToArr($questions[$i]->question);
                $data_question['qnid'] = $qnid;
                $data_question['qnum'] = $i + 1;
                $question = Question::add($data_question);
                $qtype = $data_question['qtype'];
                if ($qtype != 3 && $qtype != 4 && $qtype != 5) {
                    $data_option = functions::objectToArr($questions[$i]->option);
                    if (!is_null($questions[$i]->problem)) {
                        $data_problem = functions::objectToArr($questions[$i]->problem);
                    }
                    $qid = $question->qid;
                    Option::add($data_option, $data_problem ?? null, $qid, $qtype);
                }
            }
            $questions = Question::getAllQuestions($qnid);
            return response()->json([
                'questionnaire' => $questionnaire ?? null,
                'questions' => $questions,
                'question' => new Question()
            ]);
        }
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        return view('Question.add', [
            'questionnaire' => $questionnaire,
            'question' => new Question()
        ]);
//        return response()->json([
//            'questionnaire' => $questionnaire,
//            'question' => new Question()
//        ]);
    }

    public function update(Request $request, $qnid) {
        Question::deleteAll($qnid);
        Option::deleteAll($qnid);
        $newQuestionnaire = $this->addQuestion($request, $qnid);
        return $newQuestionnaire;
    }

    public function publish($qnid) {
        $data = ['status' => 1];
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $name = $questionnaire->name;
        $link = url('submit/qnid/' . $qnid);
        return response()->json([
            'name' => $name,
            'link' => $link
        ]);
    }

    public function submit(Request $request, $qnid) {
        if ($request->isMethod('POST')) {
            if ($request->session()->has('data')) {
                $user_number = $request->session()->get('data')['user_number'];
            }
            $ip = functions::getIp();
            $data_submit = [
                'qnid' => $qnid,
                'user_number' => $user_number ?? null,
                'ip' => $ip
            ];
            $submit = Submit::add($data_submit);
            $sid = $submit->sid;
            $data_answers = $request->all();
            for ($i = 0; $i < count($data_answers); $i++) {
                $qid = $data_answers[$i]->qid;
                $question = Question::getQuestionByQid($qid);
                $qtype = $question->qtype;
                $data_answer = functions::objectToArr($data_answers[$i]->answers);
                $answers[$i] = Answer::add($data_answer, $sid, $qid, $qtype);
            }
            return response()->json([
                'answers' => $answers ?? null
            ]);
        }
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $questions = Question::getAllQuestions($qnid);
        return response()->json([
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ]);
    }

    public function test() {
        return view('test');

    }
}