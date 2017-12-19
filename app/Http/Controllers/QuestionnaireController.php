<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Editor;
use App\Helpers\functions;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;
use App\Usr;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuestionnaireController extends Controller
{
    public function add(Request $request, $status)
    {
        $data = $request->all();
        $twt_name = $request->session()->get('data')['twt_name'];
        $data_questionnaire = $data['questionnaire'] ?? null;
        if ($data_questionnaire == null) {
            return response()->json([
                'message' => '请填写问卷标题'
            ]);
        }
        $data_questions = $data['questions'] ?? null;
        $qcount = count($data_questions);
        $data_questionnaire['twt_name'] = $twt_name;
        $data_questionnaire['qcount'] = $qcount;
        $data_questionnaire['status'] = $status;
        $data_questionnaire['num'] = 0;
        $data_questionnaire['recovery'] = 0;
        $data_questionnaire['issetddl'] = 0;
        $data_questionnaire['hasnumber'] = 1;
        $data_questionnaire['ischecked'] = 0;
        $data_questionnaire['onceanswer'] = 0;
        $data_questionnaire['verifiedphone'] = 0;
        $time = date('Y-m-d H:i:s', time());
        $data_questionnaire['created_at'] = $time;
        $data_questionnaire['updated_at'] = $time;
        $questionnaire = Questionnaire::add($data_questionnaire);
        $qnid = $questionnaire->qnid;
        $editor = Editor::add($twt_name, $qnid);
        $eid = $editor->id;
        $data_editor = ['eid' => $eid];
        return response()->json('');
        Questionnaire::updateByQnid($qnid, $data_editor);
        $response = ['qnid' => $qnid];
        if ($qcount == 0) {
            return response()->json($response);
        }
        $this->store($data, $qnid);
        return response()->json($response);
    }

    public function update(Request $request, $status, $qnid)
    {
        $data = $request->all();
        $data_questionnaire = $data['questionnaire'] ?? null;
        if ($data_questionnaire == null) {
            return response()->json([
                'message' => '请填写问卷标题'
            ]);
        }
        $data_questions = $data['questions'] ?? null;
        $qcount = count($data_questions);
        $time = date('Y-m-d H:i:s', time());
        $data_questionnaire['updated_at'] = $time;
        $data_questionnaire['qcount'] = $qcount;
        $data_questionnaire['status'] = $status;
        Questionnaire::updateByQnid($qnid, $data_questionnaire);
        Question::deleteAll($qnid);
        Option::deleteAll($qnid);
        $response = ['qnid' => $qnid];
        if ($qcount == 0) {
            return response()->json($response);
        }
        $this->store($data, $qnid);
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

    public function getResponseOfQuestionnaire($qnid, $src = null)
    {
        if ($src == 'answer') {
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
        }
        $response = $this->getDataOfQuestionnaire($qnid);
        return response()->json($response);
    }

    public function getResponseOfAnswer($qnid) {
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
        $response = $this->getDataOfQuestionnaire($qnid);
        return response()->json($response);
    }

    public function getDataOfQuestionnaire($qnid) {
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $questions = Question::getAllQuestions($qnid);
        if (count($questions) == 0) {
            $questions = array();
        }
        return [
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ];
    }

    public function submit(Request $request, $qnid)
    {
        $real_name = null;
        if ($request->session()->has('data')) {
            $twt_name = $request->session()->get('data')['twt_name'];
            $phone = $request->session()->get('data')['phone'] ?? null;
            $real_name = $request->session()->get('data')['real_name'];
        }
        $questionnaire_data = Questionnaire::getdata($qnid);
        $creator_name = $questionnaire_data['twt_name'];
        $creator_type = Usr::getTypeByName($creator_name);
        if($creator_type == 1 && $questionnaire_data['ischecked'] == 1){
            $name = $real_name;
        }
        else{
            $name = null;
        }
        $ip = $request->getClientIp();
        $data_submit = [
            'qnid' => $qnid,
            'twt_name' => $twt_name ?? null,
            'ip' => $ip,
            'phone' => $phone ?? null,
            'real_name' => $name,
        ];
        $submit = Submit::add($data_submit);
        $sid = $submit->sid;
        $data_answers = $request->input('answers');
        for ($i = 0; $i < count($data_answers); $i++) {
            $data_answer = $data_answers[$i];
            $answers[$i] = Answer::add($data_answer, $sid, $qnid, $i);
            if (is_numeric($answers[$i])) {
                if ($answers[$i] == -1) {
                    Answer::deleteBySid($sid);
                    Submit::deleteBySid($sid);
                    return response()->json([
                        'message' => '有未答的题目',
                        'qnum' => $i + 1,
                    ]);
                } else if ($answers[$i] == 0) {
                    continue;
                } else {
                    Answer::deleteBySid($sid);
                    Submit::deleteBySid($sid);
                    return response()->json([
                        'message' => '文本验证',
                        'qnum' => $i + 1,
                        'error' => $answers[$i],
                    ]);
                }
            }
        }
        $new_recovery = Submit::count_answers($qnid);
        $update_recovery = Questionnaire::updateByQnid($qnid, ['recovery' => $new_recovery]);
        return response()->json([
            'answers' => $answers ?? null
        ]);
    }

    public function qinfo($qnid) {
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        $qstatus = $questionnaire->status;
        $ischecked = $questionnaire->ischecked;
        $onceanswer = $questionnaire->onceanswer;
        $verifiedphone = $questionnaire->verifiedphone;
        $twt_name = $questionnaire->twt_name;
        $issupermng = Usr::getTypeByName($twt_name);
        return response()->json([
            'qstatus' => $qstatus,
            'ischecked' => $ischecked,
            'onceanswer' => $onceanswer,
            'verifiedphone' => $verifiedphone,
            'issupermng' => $issupermng
        ]);
    }

    public function ifAnswered($qnid, Request $request){
        $twt_name = null;
        $info = null;
        if ($request->session()->has('data')) {
            $twt_name = $request->session()->get('data')['twt_name'];
        }
        if($twt_name != null){
            $data = Submit::getdata($qnid, $twt_name);
            if(count($data) < 1){
                $info = 0;
            }

            else{
                $info = 1;
            }
        }
        return response()->json([
            'info' => $info,
        ]);
    }
}