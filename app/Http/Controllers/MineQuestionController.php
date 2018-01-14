<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Helpers\forsupermanager;
use App\Submit;
use Illuminate\Http\Request;
use App\Questionnaire;
use App\Question;
use App\Option;
use App\Usr;
use App\Editor;
use Carbon\Carbon;
use App\Helpers\answers;
use PHPExcel;
use Maatwebsite\Excel\Facades\Excel;

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
//        if($questionnaire != null){
//            foreach ($questionnaire as $key => $val){
//                if($val != null && $val->recovery_at != null){
//                    $today_at = Carbon::now();
//                    if($val->recovery_at <= $today_at){
//                        $status = 2;
//                        $update = Questionnaire::updateByQnid($val->qnid, ['status' => $status]);
//                    }
//                    else{
//                        $status = 1;
//                        $update = Questionnaire::updateByQnid($val->qnid, ['status' => $status]);
//                    }
//                }
//            }
//        }
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
            'questionnaire' => $questionnaire ?? array(),
            'eid' => $eid,
        ]);
    }

//    //问卷缩略图页面
//    public function mine(Request $request){
//        $order_status = $request->input('order_status');
//        $order_sequence = $request->input('order_sequence');
//        $twt_name = $request->session()->get('data')['twt_name'];
//
////        $data = $request->input('data');
////        if($data){
////            $find = Questionnaire::reach($data);
////            if(!$find){
////                return redirect('/minequestion/false');
////            }  //报错页面
////            return response()->json([
////                'find' => $find,
////            ]);
////        }
//        $questionnaires = Editor::questionnaires($twt_name);
//        $id = array();
//        if ($questionnaires) {
//            foreach ($questionnaires as $key=>$val) {
//                $id[$key] = $val->qnid;
//            }
//        }
//        if($id) {
//            $questionnaire = Questionnaire::sequence($order_status, $order_sequence, $id);
//            return response()->json([
//                'questionnaire' => $questionnaire ?? null,
//            ]);
//        }
//    }
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

    //问卷展开 提交数据折线图
    public function submitNum($qnid){
        $questionnaire_data = Questionnaire::getdata($qnid);
        $submit_answers = Submit::answers($qnid);
        $created_at = date('Y-m-d', strtotime($questionnaire_data['created_at']));
        $today_at = date('Y-m-d', strtotime(Carbon::now()));
        $everyday_ans = array();
        for($i = $created_at;$i <= $today_at;$i = date('Y-m-d', strtotime("$i +1 day"))){
            if(count($submit_answers) >= 1){
                foreach ($submit_answers as $key => $val){
                    $time = date('Y-m-d', strtotime($val['created_at']));
                    if($time == $i){
                        if(!isset($everyday_ans[$time]['number'])){
                            $everyday_ans[$time]['number'] = 1;
                            $everyday_ans[$time]['time'] = $time;
                        }
                        else{
                            $everyday_ans[$time]['number'] = $everyday_ans[$time]['number']+1;
                        }
                    }
                }
            }
        }
        $everyday_ans = array_values($everyday_ans);
        return response()->json([
            'everyday_ans' => $everyday_ans,
        ]);
    }

    //问卷展开 浏览量和提交量
    public function browseAndSubmit($qnid){
        $questionnaire_data = Questionnaire::getdata($qnid);
        $submit_answers = Submit::answers($qnid);
        $count_answers = count($submit_answers);
        return response()->json([
            'questionnaire_data' => $questionnaire_data,
            'count_answers' => $count_answers,
        ]);
    }

    //问卷展开 表单
    public function overview($qnid, $page){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
        $questionnaire_data = Questionnaire::getdata($qnid);
        $creator_type = Usr::getTypeByName($questionnaire_data['twt_name']);
        $questions = Question::getquestions($qnid);
        $editors = Editor::getdata($qnid);
        $submit_time = array();
        $formanswers = array();
        $answer_ques = array();
        $stu_info = array();
        $answer_final = array();
        $submit = Submit::copeSubmit($qnid, $page);
        $submit_count = Submit::count_answers($qnid);
        $page = ceil($submit_count/25);
        if(count($submit) > 0){
            foreach($submit as $key=>$val){
                $answer = Answer::getAnswerBySid($val['sid']);
                $submit_time[$val['sid']]['date']['qid'] = 'date';
                $time = strtotime(Submit::getTimeBySid($val['sid']));
                $submit_time[$val['sid']]['date']['answer'] = date('Y-m-d H:i:s', $time);
                if ($creator_type == 1) {
                    $twt_name = Submit::getNameBySid($val['sid']);
                    $real_name = Submit::getRealnameBySid($val['sid']);
                    $user_number = Usr::getNumberByName($twt_name);
                    $stu_info[$val['sid']][] = new forsupermanager('name', $real_name);
                    $stu_info[$val['sid']][] = new forsupermanager('studentid', $user_number);
                } else {
                    $stu_info = array([]);
                }
                if(count($answer) > 0){
                    foreach ($answer as $keys=>$vals){
                        $answer_ques[$val['sid']][$vals['qid']][] = $vals;
                    }
                }
            }
        }

//        if(count($answers) >= 1) {
//            foreach ($answers as $val) {
//                $answer_ques[$val['sid']][$val['qid']][] = $val;
//                $submit_time[$val['sid']]['date']['qid'] = 'date';
//                $time = strtotime(Submit::getTimeBySid($val['sid']));
//                $submit_time[$val['sid']]['date']['answer'] = date('Y-m-d H:i:s', $time);
//                if ($creator_type == 1) {
//                    $twt_name = Submit::getNameBySid($val['sid']);
//                    $real_name = Submit::getRealnameBySid($val['sid']);
//                    $user_number = Usr::getNumberByName($twt_name);
//                    $stu_info[$val['sid']][] = new forsupermanager('name', $real_name);
//                    $stu_info[$val['sid']][] = new forsupermanager('studentid', $user_number);
//                } else {
//                    $stu_info = array([]);
//                }
//            }
//        }
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
                    $questions = Question::getquestions($qnid);
                    if(count($questions) >= 1){
                        foreach ($questions as $key => $val){
                            $qtype = 0;
                            $finalanswer = [
                                'option' => '',
                            ];
                            $formanswers[$keys][$val['qid']] = new answers($val, $finalanswer, $qtype);
                        }
                    }
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
        if($creator_type == 0){
            $formanswers_special = array_replace_recursive($formanswers, $submit_time);
        }
        else{
            $formanswers_pro = array_replace_recursive($stu_info, $formanswers);
            $formanswers_special = array_replace_recursive($formanswers_pro, $submit_time);
        }
        $formanswers_special = array_values($formanswers_special);
        if($formanswers_special != null) {
            foreach ($formanswers_special as $key => $val) {
                $answer_final[$key] = array_values($formanswers_special[$key]);
            }
        }
        return response()->json([
            'questions' => $questions,
            'editors' => $editors,
            'answers' => $answer_final,
            'page' => $page,
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

    //设置页面
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
                'verifiedphone' => $verifiedphone,

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

    //问卷展开 设置问卷收集状态
    public function installCollect($qnid, Request $request)
    {
        $questionnaire_data = Questionnaire::getQuestionnaire($qnid);
//        if ($questionnaire_data['recovery_at'] != null) {
//            $today_at = Carbon::now();
//            if ($questionnaire_data['recovery_at'] <= $today_at) {
//                $status = 2;
//                $update = Questionnaire::updateByQnid($qnid, ['status' => $status]);
//            } else {
//                $status = 1;
//                $update = Questionnaire::updateByQnid($qnid, ['status' => $status]);
//            }
//        }
        if($request->isMethod('POST')) {
            $iscollect = $request->input('iscollect');
            if ($questionnaire_data['status'] == 2) {
                $iscollect = 2;
            }
            if($iscollect == 0){
                $iscollect = 2;
                $time = date('Y-m-d H:i:s', time());
                $issetddl = 1;
                $install = [
                    'status' => $iscollect,
                    'recovery_at' => $time,
                    'issetddl' => $issetddl,
                ];
                $install_add = Questionnaire::update_collect($qnid, $install);
            }
            else{
                $iscollect = 1;
                $issetddl = 0;
                $install = [
                    'status' => $iscollect,
                    'recovery_at' => null,
                    'issetddl' => $issetddl,
                ];
                $install_add = Questionnaire::update_collect($qnid, $install);
            }
        }
        $questionnaire_data = Questionnaire::getQuestionnaire($qnid);
        return response()->json([
            'questionnaire_data' => $questionnaire_data,
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

    public function verify($qnid, Request $request){
        $twt_name = $request->session()->get('data')['twt_name'];
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        if($twt_name == $questionnaire['twt_name']){
            $response = 1;
        }
        else{
            $response = 0;
        }
        return response()->json($response);
    }

    public function export($qnid){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
        $answers = Submit::copeAnswer($qnid);
        Excel::create('问卷回答',function($excel) use ($answers){
            $excel->sheet('score', function($sheet) use ($answers) {
                $row = 1;
                foreach ($answers as $keys => $vals) {
                    $col = 1;
                    foreach ($answers[$keys] as $key => $val) {
                        $sheet->rows($row, $col, $answers[$keys][$key]['answer'] ?? '');
                        $col++;
                    }
                    $row++;
                }

            });
        })->export('xls');
//        $obj = new PHPExcel();
//        $obj->getProperties()-> setTitle("问卷回答");
//        $row = 1;
//        foreach ($answers as $keys=>$vals){
//            $col = 1;
//            foreach ($answers[$keys] as $key=>$val){
//                $obj->getActiveSheet()-> setCellValueByColumnAndRow($col, $row, $answers[$keys][$key]['answer'] ?? '');
//                $col++;
//            }
//            $row++;
//        }
      //  return response()->json($obj);
    }


}
