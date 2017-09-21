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
            $data_questionnaire['twt_name'] = $request->session()->get('data')['twt_name'];
            $data_questionnaire['qcount'] = 0;
            $data_questionnaire['status'] = 1;
            $questionnaire = Questionnaire::add($data_questionnaire);
            $qnid = $questionnaire->qnid;
//            return redirect('edit/qnid/' . $qnid);
            return response()->json([
                'qnid' => $qnid
            ]);
        }
        return view('Questionnaire.add');
    }

    public function addQuestion(Request $request, $qnid) {
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $data_questionnaire = $data['questionnaire'];
            $data_questions = $data['questions'];
            $qcount = count($data_questions);
            $data_questionnaire['qcount'] = $qcount;
            $questionnaire = Questionnaire::updateByQnid($qnid, $data_questionnaire);
            for ($i = 0; $i < $qcount; $i++) {
                $data_question = $data_questions[$i]['question'];
                $data_question['qnid'] = $qnid;
                $data_question['qnum'] = $i + 1;
                $question = Question::add($data_question);
                $qtype = $data_question['qtype'];
                if ($qtype != 3 && $qtype != 4 && $qtype != 5) {
                    $data_option = $data_questions[$i]['options'];
                    $data_problem = $data_questions[$i]['problem'] ?? null;
                    $qid = $question->qid;
                    Option::add($data_option, $data_problem, $qnid, $qid, $qtype);
                }
            }
            $questions = Question::getAllQuestions($qnid);
            return response()->json([
                'questionnaire' => $questionnaire ?? null,
                'questions' => $questions,
//                'question' => new Question()
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
        $questionnaire = Questionnaire::updateByQnid($qnid, $data);
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
                $twt_name = $request->session()->get('data')['twt_name'];
            }
            $ip = functions::getIp();
            if (!Submit::verifyRepeat($twt_name ?? null, $ip)) {
                return response()->json([
                    'status' => 0
                ]);
            }
            $data_submit = [
                'qnid' => $qnid,
                'twt_name' => $twt_name ?? null,
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
                $answers[$i] = Answer::add($data_answer, $qnid, $sid, $qid, $qtype);
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
}