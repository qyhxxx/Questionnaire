<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Submit;
use Illuminate\Http\Request;
use App\Questionnaire;
use App\Question;
use App\Option;
use App\Usr;
use App\Editor;
use Carbon\Carbon;
use App\Helpers\answers;

class MineQuestionController extends Controller
{
    //初始返回数据
    public function questionnaire(Request $request){
        $twt_name = $request->session()->get('data')['twt_name'];
        $eid = Editor::geteid($twt_name);
        $questionnaire = null;
        for ($i = 0; $i < count($eid); $i++) {
            $questionnaire[$i] = Questionnaire::getQuestionnaires($eid[$i]);
        }
        if($questionnaire != null){
            foreach ($questionnaire as $key => $val){
                if($val->recovery_at != null){
                    $today_at = Carbon::now();
                    if($val->recovery_at <= $today_at){
                        $status = 2;
                        $update = Questionnaire::updateByQnid($val->qnid, ['status' => $status]);
                    }
                    else{
                        $status = 1;
                        $update = Questionnaire::updateByQnid($val->qnid, ['status' => $status]);
                    }
                }
            }
        }
        for ($i = 0; $i < count($eid); $i++) {
            $questionnaire[$i] = Questionnaire::getQuestionnaires($eid[$i]);
        }
//        $questionnaires = Questionnaire::getQuestionnaireByname($twt_name);
//        $qnid = array();
//        foreach ($questionnaires as $keys=>$values){
//            $qnid[$keys] = $values['qnid'];
//        }
//        $submit_answers = Submit::getsubmit($twt_name);
//        $count_answers = array();
//        foreach ($qnid as $value){
//            foreach ($submit_answers as $key=>$val){
//                if($val['qnid'] == $value){
//                    if(!isset($count_answers[$val['qnid']])){
//                        $count_answers[$val['qnid']] = 1;
//                    }
//                    else{
//                        $count_answers[$val['qnid']]++;
//                    }
//                }
//            }
//        }
        return response()->json([
            'questionnaire' => $questionnaire ?? null,
            'eid' => $eid,
        ]);
    }

    //问卷缩略图页面
    public function mine(Request $request){
        $order_status = $request->input('order_status');
        $order_sequence = $request->input('order_sequence');
        $twt_name = $request->session()->get('data')['twt_name'];

//        $data = $request->input('data');
//        if($data){
//            $find = Questionnaire::reach($data);
//            if(!$find){
//                return redirect('/minequestion/false');
//            }  //报错页面
//            return response()->json([
//                'find' => $find,
//            ]);
//        }
        $questionnaires = Editor::questionnaires($twt_name);
        $id = array();
        if ($questionnaires) {
            foreach ($questionnaires as $key=>$val) {
                $id[$key] = $val->qnid;
            }
        }
        if($id) {
            $questionnaire = Questionnaire::sequence($order_status, $order_sequence, $id);
            return response()->json([
                'questionnaire' => $questionnaire ?? null,
            ]);
        }
    }
//    //问卷缩略图页面搜索问卷
//    public function reach(Request $request){
//        $data = $request->input('data');
//        $find = Questionnaire::reach($data);
//        if(!$find){
//            return redirect('/minequestion/false');
//        }  //报错页面
//        return response()->json([
//            'find' => $find,
//        ]);
//    }
    //问卷展开[概述、设置]
    public function overview($qnid){
        $questionnaire_data = Questionnaire::getdata($qnid);
        $questions = Question::getquestions($qnid);
        $count_questions = count($questions);
        $editors = Editor::getdata($qnid);
        $submit_answers = Submit::answers($qnid);
        $count_answers = count($submit_answers);
        //     $answers = Answer::getanswers($qnid);
        $created_at = $questionnaire_data['created_at'];
        $created_day = strtotime($created_at);
        $today_at = Carbon::now();
        $today_day = strtotime($today_at);
        $everyday_ans = array();
     //   $j = 0;
        for($i = $created_day;$i <= $today_day;$i = strtotime('+1 day', $i)){
            foreach ($submit_answers as $key => $val){
                $time = date('Y-m-d', strtotime($val['created_at']));
                    if($time == date('Y-m-d',$i)){
                        if(!isset($everyday_ans[$time]['number'])){
                            $everyday_ans[$time]['number'] = 1;
                            $everyday_ans[$time]['time'] = $time;
                        }
                        else{
                            $everyday_ans[$time]['number'] = $everyday_ans[$j]['number']+1;
                        }
                    }
            }
          //  $j++;
        }
        $everyday_ans = array_values($everyday_ans);
        $answer = array();
        $formanswers = array();

        $answers = Answer::getmanyanswers($qnid);
        if($answers != null) {
            foreach ($answers as $val) {
                $answer[$val['sid']][$val['qid']][] = $val;
            }
        }
        $i = 0;
        $answer_ques = array();
        $answer_sub = array_values($answer);
        foreach ($answer_sub as $key => $val){
            $answer_ques[$key] = array_values($answer_sub[$key]);
        }
        if(count($answer_ques) >= 1) {
            foreach ($answer_ques as $keys => $value) {
                if(count($answer_ques[$keys]) >= 1) {
                    foreach ($answer_ques[$keys] as $qid => $info) {
                        if(count($answer_ques[$keys][$qid]) >= 1) {
                            foreach ($answer_ques[$keys][$qid] as $num => $val1) {
                                $question = Question::getonequestion($qnid, $val1['qid']);
                                $qtype = $question['qtype'];
                                if ($qtype == 0) {
                                    $finalanswer0 = $val1['option'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer0, $qtype);
                                } elseif ($qtype == 1) {
                                    $finalanswer1[$num] = $val1['option'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer1, $qtype);
                                } elseif ($qtype == 2) {
                                    $finalanswer2[] = [
                                        'okey' => $val1['okey'],
                                        'answer' => $val1['answer'],
                                    ];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer2, $qtype);
                                } elseif ($qtype == 3) {
                                    $finalanswer3 = $val1['answer'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer3, $qtype);
                                } elseif ($qtype == 4) {
                                    $finalanswer4 = $val1['answer'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer4, $qtype);
                                } elseif ($qtype == 5) {
                                    $finalanswer5 = $val1['answer'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer5, $qtype);
                                } elseif ($qtype == 6) {
                                    $finalanswer6[$num] = $val1['option'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer6, $qtype);
                                } elseif ($qtype == 7) {
                                    // $option[$val1['pkey']] = $val1['option'];
                                    $finalanswer7[$num] = [
                                        'pkey' => $val1['pkey'],
                                        'problem' => $val1['problem'],
                                        'okey' => $val1['okey'],
                                        //     'option' => $option,
                                    ];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer7, $qtype);
                                } elseif ($qtype == 8) {
                                    //    $option[$val1['pkey']][] = $val1['option'];
                                    $finalanswer8[$num] = [
                                        'pkey' => $val1['pkey'],
                                        'problem' => $val1['problem'],
                                        'okey' => $val1['okey'],
                                        //        'option' => $option,
                                    ];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer8, $qtype);
                                } elseif ($qtype == 9) {
                                    $finalanswer9[$num] = [
                                        'pkey' => $val1['pkey'],
                                        'problem' => $val1['problem'],
                                        'answer' => $val1['answer'],
                                    ];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer9, $qtype);
                                } elseif ($qtype == 10) {
                                    $finalanswer10[$num] = [
                                        'okey' => $val1['okey'],
                                        'option' => $val1['option'],
                                    ];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer10, $qtype);
                                }
                            }
                        }
                        elseif(count($answer_ques[$keys][$qid]) < 1){
                            $formanswers[$keys][$qid] = array();
                        }
                    }
                }
                elseif(count($answer_ques[$keys]) < 1){
                    $formanswers[$keys][] = array();
                }
//                else{
//                    $ishidden = Submit::submitIshidden($keys);
//                    if($ishidden = 1){
//                        continue;
//                    }
//                }
            }

        }
        else{
            $formanswers = array();
        }

        return response()->json([
            'questionnaire_data' => $questionnaire_data,
            'questions' => $questions,
            'editors' => $editors,
            'answers' => $formanswers,
            'count_answers' => $count_answers,
            'everyday_ans' => $everyday_ans,
        ]);
    }

//    public function installInfo($qnid){
//        $questionnaire_data = Questionnaire::getQuestionnaire($qnid);
//        if($questionnaire_data->recovery_at == null){
//            $questionnaire_data->recovery_at = '';
//        }
//        return response()->json([
//            'questionnaire_data' => $questionnaire_data,
//        ]);
//    }
    public function install($qnid, Request $request){
        $questionnaire_data = Questionnaire::getQuestionnaire($qnid);
        if($request->isMethod('POST')){
            $hasnumber = $request->input('hasnumber');
            $recovery_at = $request->input('recoveryat');
            $ischecked = $request->input('ischecked');
            $onceanswer = $request->input('onceanswer');
            $issetddl = $request->input('issetddl');
            $verifiedphone = $request->input('verifiedphone');
            if($recovery_at == '' && $questionnaire_data['issetddl'] == 0){
                $issetddl = 0;
            }
            elseif($recovery_at == '' && $questionnaire_data['issetddl'] == 1){
                $issetddl = 1;
            }
            $recovery_time = '';
            if($recovery_at != ''){
                $recovery_time = date('Y-m-d H:i:s', $recovery_at / 1000);
            }
            $install = [
                'hasnumber' => $hasnumber,
                'recovery_at' => $recovery_time,
                'ischecked' => $ischecked,
                'onceanswer' => $onceanswer,
                'issetddl' => $issetddl,
                'verifiedphone' => $verifiedphone
            ];
            $install_add = Questionnaire::update_install($qnid, $install);
            $twt_name = $request->input('twt_name');
            if($twt_name != null){
                foreach ($twt_name as $key=>$value){
                    $editor_add = Editor::add($value, $qnid);
                }
            }

//            if($allkilled){
//                Answer::allkilled();
//            }
//            if($partkilled){
//                Answer::partkilled($partkilled);
//            }
        }
        $questionnaire_data = Questionnaire::getQuestionnaire($qnid);
        $twt_name = $questionnaire_data->twt_name;
        $usr = Usr::getUsr($twt_name);
        return response()->json([
            'questionnaire_data' => $questionnaire_data,
            'issupermng' => $usr->type
        ]);
    }

    public function killed($qnid, Request $request){
        $allkilled = $request->input('allkilled');
        $delete = '';
        if($allkilled == $qnid){
            $delete_ans = Answer::allkilled($qnid);
            $delete_sub = Submit::allkilled($qnid);
            $delete = 1;
        }
        return response()->json($delete);
    }
//    //问卷展开[数据]
//    public function answerdata($qnid,Request $request){
//        $qid = $request->input('qid');
//        $question = Question::getonequestion($qnid,$qid);
//        $option = Option::getQcontentsByQid($qid);
//        $option_amount = count($option);
//        $answers = Answer::getdata($qid);
//        $answers_amount = count($answers);
//        foreach ($answers as $val){
//            $answers_amount[$val->order]++;
//        }
//        return response()->json([
//            'question' => $question,
//            'option' => $option,
//            'answers_amount' => $answers_amount,
//        ]);
//    }
//    //发布(二维码)
//    public function publish($qnid){
//        $QrCode = QrCode::encoding('UTF-8')->size(100)->generate(public_path('/submit/qnid/{qnid}'));
//        return response()->json([
//            'QrCode' => $QrCode,
//        ]);
//    }



}