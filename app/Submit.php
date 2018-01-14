<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Answer;
use App\Usr;
use App\Helpers\forsupermanager;
<<<<<<< HEAD
use App\Helpers\answers;
=======
>>>>>>> 4486f3084896f72d377ba45db6d6c9fb7625ab44

class Submit extends Model {
    protected $table = 'submits';

    protected $primaryKey = 'sid';

    protected $fillable = ['qnid', 'twt_name', 'ip', 'phone', 'real_name'];

    public $timestamps = true;

    public static function add($data) {
        $submit = self::create($data);
        return $submit;
    }

    public static function isRepeat($qnid, $twt_name, $phone = null) {
        if ($phone) {
            $submit = self::where([
                'qnid' => $qnid,
                'phone' => $phone
            ])->first();
        } else {
            $submit = self::where([
                'qnid' => $qnid,
                'twt_name' => $twt_name
            ])->first();
        }
        if ($submit != null) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getSidArr($qnid) {
        $submits = self::where('qnid', $qnid)->get()->toArray();
        $sidArr = array();
        if (!empty($submits)) {
            foreach ($submits as $submit) {
                $sidArr[] = $submit['sid'];
            }
            $sidArr = array_unique($sidArr);
        }
        return $sidArr;
    }

    public static function getdata($qnid, $twt_name){
        $data = self::where(['qnid' => $qnid, 'twt_name' => $twt_name])->get();
        return $data;
    }

    //用了别删
    public static function count_answers($qnid){
        $data = self::where('qnid',$qnid)->get();
        $count = count($data);
        return $count;
    }

    public static function answers($qnid){
        $data = self::where('qnid',$qnid)->get();
        return $data;
    }

    public static function getsubmit($twt_name){
        $submit = self::where('twt_name', $twt_name)->get();
        return $submit;
    }

    public static function deleteBySid($sid) {
        self::where('sid', $sid)->delete();
    }

    public static function allkilled($qnid){
        $allkilled = self::where('qnid', $qnid)->delete();
        return $allkilled;
    }

    public static function getNameBySid($sid){
        $data = self::where('sid', $sid)->first();
        $twt_name = $data['twt_name'];
        return $twt_name;
    }

    public static function getRealnameBySid($sid){
        $data = self::where('sid', $sid)->first();
        $real_name = $data['real_name'];
        return $real_name;
    }

    public static function getTimeBySid($sid){
        $data = self::where('sid', $sid)->first();
        $time = $data['created_at'];
        return $time;
    }

    public static function top500($qnid) {
        $submits = self::where('qnid', $qnid)->take(2400)->get();
        return $submits;
    }

<<<<<<< HEAD
    public static function copeAnswer($qnid){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
        $number = self::count_answers($qnid);
        $page = ceil($number/25);
        $questionnaire_data = Questionnaire::getdata($qnid);
        $creator_type = Usr::getTypeByName($questionnaire_data['twt_name']);
        $answer_ques = array();
        $questions = Question::getquestions($qnid);
        $formanswers = array();
        if(count($questions) >= 1){
            foreach ($questions as $qkey=>$qval){
                $formanswers['first'][$qval['qid']] = $qval['topic'];
            }
        }
        $formanswers['first'] = array_collapse(['name', 'studentid', 'submitTime'], $formanswers['first']);
        for($i = 1;$i <= $page;$i++){
            $submit = self::copeSubmit($qnid, $i);
            if(count($submit) > 0){
                foreach($submit as $key=>$val){
                    $answer = Answer::getAnswerBySid($val['sid']);
               //     $submit_time[$val['sid']]['date']['qid'] = 'date';
                    $time = strtotime(Submit::getTimeBySid($val['sid']));
                //    $submit_time[$val['sid']]['date']['answer'] = date('Y-m-d H:i:s', $time);
                    $submit_time[$val['sid']]['date'] = new forsupermanager('date', date('Y-m-d H:i:s', $time));
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
                    else{
                        $answer_ques = array([]);
                    }
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
                    }
                }
                else{
                    $formanswers = array();
                }

            }
        };
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
        return $answer = array(
            'answer' => $answer_final ?? '',
        );

    }

    public static function copeSubmit($qnid, $page){
        $submit = self::where('qnid', $qnid)->forPage($page, 25)->get();
=======
//    public static function copeAnswer($qnid){
//        $submit = self::where('qnid', $qnid)->get();
//        $questionnaire_data = Questionnaire::getdata($qnid);
//        $creator_type = Usr::getTypeByName($questionnaire_data['twt_name']);
//        $answer_cope = array();
//        $answers = self::chunk(100, function ($submit, $creator_type){
//            foreach($submit as $key=>$val){
//                $answer = Answer::getAnswerBySid($val['sid']);
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
//                foreach ($answer as $keys=>$vals){
//                    $answer_cope[$val['sid']][$vals['qid']][] = $vals;
//                }
//            }
//        });
//        return $answer = array(
//            'answer' => $answer_cope,
//            'stu_info' => $stu_info,
//
//        );
//    }

    public static function copeSubmit($qnid, $page){
        $submit = self::where('qnid', $qnid)->forPage($page, 200)->get();
>>>>>>> 4486f3084896f72d377ba45db6d6c9fb7625ab44
        return $submit;
    }
}