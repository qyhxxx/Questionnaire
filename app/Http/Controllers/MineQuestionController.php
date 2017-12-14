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
    public function overview($qnid, Request $request){
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
        $j = 0;
        for($i = $created_day;$i <= $today_day;$i = strtotime('+1 day', $i)){
            foreach ($submit_answers as $key => $val){
                $time = date('Y-m-d', strtotime($val['created_at']));
                    if($time == date('Y-m-d',$i)){
                        if(!isset($everyday_ans[$j]['number'])){
                            $everyday_ans[$j]['number'] = 1;
                            $everyday_ans[$j]['time'] = $time;
                        }
                        else{
                            $everyday_ans[$j]['number'] = $everyday_ans[$j]['number']+1;
                        }
                    }
            }
            $j++;
        }
        $answer = null;
        $formanswers = null;
        $answers = Answer::getmanyanswers($qnid);
        if($answers != null){
            foreach ($answers as $val){
                $answer[$val['sid']][$val['qid']] = $val;
            }
        }
        if($answer != null){
            foreach ($answer as $key=>$value){
                foreach ($answer[$key] as $qid=>$info){
                    $question = Question::getonequestion($qnid, $info['qid']);
                    $option = Option::getQcontentsByQid($info['qid']);
                    $oneanswer =  Answer::getoneanswer($key, $info['qid']);
                    $qtype = $question['qtype'];
                    $finalanswer = null;
                    if($info['option'] != null || $info['answer'] != null) {
                        if ($qtype == 0) {
                            $finalanswer = [
                                'okey' => $info['okey'],
                                'option' => $info['option'],
                            ];
                        } elseif ($qtype == 1 || $qtype == 10) {
                            $finalanswer[$info['qid']][] = [
                                'okey' => $info['okey'],
                                'option' => $info['option'],
                            ];
                        } elseif ($qtype == 2) {
                            $finalanswer[$info['qid']][] = [
                                'okey' => $info['okey'],
                                'answer' => $info['answer'],
                            ];
                        } elseif ($qtype == 3 || $qtype == 4 || $qtype == 5) {
                            $finalanswer = [
                                'okey' => $info['okey'],
                                'answer' => $info['answer'],
                            ];
                        } elseif ($qtype == 6) {
                            $finalanswer[$info['qid']][] = [
                                'okey' => $info['okey'],
                                'option' => $info['option'],
                                'answer' => $info['answer'],
                            ];
                        } elseif ($qtype == 7) {
                            $option[$info['pkey']] = $info['option'];
                            $finalanswer[$info['qid']][] = [
                                'pkey' => $info['pkey'],
                                'problem' => $info['problem'],
                                'okey' => $info['okey'],
                                'option' => $option,
                            ];
                        } elseif ($qtype == 8) {
                            $option[$info['pkey']][] = $info['option'];
                            $finalanswer[$info['qid']][] = [
                                'pkey' => $info['pkey'],
                                'problem' => $info['problem'],
                                'okey' => $info['okey'],
                                'option' => $option,
                            ];
                        } elseif ($qtype == 9) {
                            $finalanswer[$info['qid']][] = [
                                'pkey' => $info['pkey'],
                                'problem' => $info['problem'],
                                'answer' => $info['answer'],
                            ];
                        }
                    }
                    $formanswers[][$info['qid']] = new answers($question, $option, $finalanswer, $qtype);
                }
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

    public function install($qnid, Request $request){
        $questionnaire_data = Questionnaire::getQuestionnaire($qnid);
        if($request->isMethod('POST')){
            $hasnumber = $request->input('hasnumber');
            $recovery_at = $request->input('recoveryat');
            $ischecked = $request->input('ischecked');
            $onceanswer = $request->input('onceanswer');
            $issetddl = $request->input('issetddl');
            if($recovery_at == null && $questionnaire_data['issetddl'] = 0){
                $issetddl = 0;
            }
            elseif($recovery_at == null && $questionnaire_data['issetddl'] = 1){
                $issetddl = 1;
            }
            $allkilled = $request->input('allkilled');
            $partkilled = $request->input('partkilled');
            if($allkilled == $qnid){
                $delete_all = Answer::allkilled($qnid);
            }
            if($partkilled != null){
                foreach ($partkilled as $key => $val){
                    $delete_part = Answer::partkilled($val);
                }
            }
            $recovery_time = null;
            if($recovery_at != null){
                $recovery_time = date('Y-m-d H:i:s', $recovery_at / 1000);
            }
            $install = [
                'hasnumber' => $hasnumber,
                'recovery_at' => $recovery_time,
                'ischecked' => $ischecked,
                'onceanswer' => $onceanswer,
                'issetddl' => $issetddl,
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
        return response()->json([
            'questionnaire_data' => $questionnaire_data,
        ]);
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