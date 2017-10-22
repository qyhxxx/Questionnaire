<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Editor;
use App\Helpers\functions;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function add(Request $request, $status)
    {
        $data = $request->all();
        $twt_name = $request->session()->get('data')['twt_name'];
        $data_questionnaire = $data['questionnaire'];
        $data_questions = $data['questions'];
        $qcount = count($data_questions);
        $data_questionnaire['twt_name'] = $twt_name;
        $data_questionnaire['qcount'] = $qcount;
        $data_questionnaire['status'] = $status;
        $data_questionnaire['num'] = 0;
        $data_questionnaire['recovery'] = 0;
        $questionnaire = Questionnaire::add($data_questionnaire);
        $qnid = $questionnaire->qnid;
        $editor = Editor::add($twt_name, $qnid);
        $eid = $editor->id;
        $data_editor = ['eid' => $eid];
        Questionnaire::updateByQnid($qnid, $data_editor);
        $this->store($data, $qnid);
        $response = $status ? ['qnid' => $qnid] : null;
        return response()->json($response);
    }

    public function update(Request $request, $status, $qnid)
    {
        $data = $request->all();
        $data_questionnaire = $data['questionnaire'];
        $data_questions = $data['questions'];
        $qcount = count($data_questions);
        $data_questionnaire['qcount'] = $qcount;
        $data_questionnaire['status'] = $status;
        Questionnaire::updateByQnid($qnid, $data_questionnaire);
        Question::deleteAll($qnid);
        Option::deleteAll($qnid);
        $this->store($data_questions, $qnid);
        $response = $status ? ['qnid' => $qnid] : null;
        return response()->json($response);
    }

    public function store($data, $qnid)
    {
        $data_questions = $data['questions'];
        $qcount = count($data_questions);
        for ($i = 0; $i < $qcount; $i++) {
            $data_question = $data_questions[$i]['question'];
            $data_question['qnid'] = $qnid;
            $data_question['qnum'] = $i + 1;
            $question = Question::add($data_question);
            $qtype = $data_question['qtype'];
            if ($qtype != 3 && $qtype != 4 && $qtype != 5) {
                $data_option = $data_questions[$i]['options'] ?? null;
                $data_problem = $data_questions[$i]['problems'] ?? null;
                $qid = $question->qid;
                Option::add($data_option, $data_problem, $qnid, $qid, $qtype);
            }
        }
    }

    public function getDataOfQuestionnaire($qnid)
    {
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $questions = Question::getAllQuestions($qnid);
        return response()->json([
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ]);
    }

    public function submit(Request $request, $qnid)
    {
        if ($request->isMethod('POST')) {
            if ($request->session()->has('data')) {
                $twt_name = $request->session()->get('data')['twt_name'];
            }
            $ip = functions::getIp();
//        if (Submit::isRepeat($qnid, $twt_name ?? null, $ip)) {
//            retrn response()->json([
//                    'status' => 0
//            ]);
//        }
            $data_submit = [
                'qnid' => $qnid,
                'twt_name' => $twt_name ?? null,
                'ip' => $ip
            ];
            $submit = Submit::add($data_submit);
            $sid = $submit->sid;
            $data_answers = $request->input('answers');
            for ($i = 0; $i < count($data_answers); $i++) {
                $data_answer = $data_answers[$i];
                $answers[$i] = Answer::add($data_answer, $sid);
            }
            $questionnaire = Questionnaire::getQuestionnaire($qnid);
            $recovery = $questionnaire['recovery'];
            $recovery = $recovery+1;
            $update_recovery = Questionnaire::updateByQnid($qnid, ['recovery' => $recovery]);
            return response()->json([
                'answers' => $answers ?? null
            ]);
        }
        $questionnaire_info = Questionnaire::getdata($qnid);
        $num = $questionnaire_info['num'];
        if(isset($num)){
            $num = $num+1;
            $data_update = [
                'num' => $num,
            ];
            $update = Questionnaire::updateByQnid($qnid, $data_update);
        }
        else{
            $num = 1;
            $data_update = [
                'num' => $num,
            ];
            $update = Questionnaire::updateByQnid($qnid, $data_update);
        }
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $questions = Question::getAllQuestions($qnid);
        return response()->json([
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ]);
    }
}