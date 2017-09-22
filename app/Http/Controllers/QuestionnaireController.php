<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Editor;
use App\Helpers\functions;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller {
    public function add(Request $request) {
        $data = $request->all();
        $twt_name = $request->session()->get('data')['twt_name'];
        $qnid = $this->store($data, $twt_name);
        $link = 'http://http://survey.twtstudio.com/submit/qnid/' . $qnid;
        return response()->json([
            'link' => $link
        ]);
    }

    public function store($data, $twt_name) {
        $data_questionnaire = $data['questionnaire'];
        $data_questions = $data['questions'];
        $qcount = count($data_questions);
        $data_questionnaire['twt_name'] = $twt_name;
        $data_questionnaire['qcount'] = $qcount;
        $data_questionnaire['status'] = 1;
        $questionnaire = Questionnaire::add($data_questionnaire);
        $qnid = $questionnaire->qnid;
        $editor = Editor::add($twt_name, $qnid);
        $eid = $editor->id;
        $data = ['eid' => $eid];
        Questionnaire::updateByQnid($qnid, $data);
        for ($i = 0; $i < $qcount; $i++) {
            $data_question = $data_questions[$i]['question'];
            $data_question['qnid'] = $qnid;
            $data_question['qnum'] = $i + 1;
            $question = Question::add($data_question);
            $qtype = $data_question['qtype'];
            if ($qtype != 3 && $qtype != 4 && $qtype != 5) {
                $data_option = $data_questions[$i]['options'];
                $data_problem = $data_questions[$i]['problems'] ?? null;
                $qid = $question->qid;
                Option::add($data_option, $data_problem, $qnid, $qid, $qtype);
            }
        }
        return $qnid;
    }

    public function update(Request $request, $qnid) {
        if ($request->isMethod('POST')) {
            Question::deleteAll($qnid);
            Option::deleteAll($qnid);
            $data = $request->all();
            $this->store($data, $qnid);
            $questionnaire = Questionnaire::getQuestionnaire($qnid);
            $questions = Question::getAllQuestions($qnid);
            return response()->json([
                'questionnaire' => $questionnaire,
                'questions' => $questions
            ]);
        }
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $questions = Question::getAllQuestions($qnid);
        return response()->json([
            'questionnaire' => $questionnaire,
            'questions' => $questions
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