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
use Illuminate\Support\Facades\DB;
use PHPExcel;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends Controller{

    public function data($qnid)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '0');
        $submit_time = array();
        $formanswers = array();
        $answer_ques = array();
        $stu_info = array();
        $answer_final = array();
        $questionnaire_data = Questionnaire::getdata($qnid);
        $creator_type = Usr::getTypeByName($questionnaire_data['twt_name']);
        $questions = Question::getquestions($qnid);
        $editors = Editor::getdata($qnid);
        $submit = Submit::getAllSubmit($qnid);
        $submit_count = Submit::count_answers($qnid);
        $page = ceil($submit_count / 15);
        if (count($submit) > 0) {
            foreach ($submit as $key => $val) {
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
                if (count($answer) > 0) {
                    foreach ($answer as $keys => $vals) {
                        $answer_ques[$val['sid']][$vals['qid']][] = $vals;
                    }
                }
            }
        }

        if (count($answer_ques) >= 1) {
            foreach ($answer_ques as $keys => $value) {
                if (count($answer_ques[$keys]) >= 1) {
                    foreach ($answer_ques[$keys] as $qid => $info) {
                        if (count($answer_ques[$keys][$qid]) >= 1) {
                            foreach ($answer_ques[$keys][$qid] as $num => $val1) {
                                $question = Question::getonequestion($qnid, $val1['qid']);
                                $qtype = $question['qtype'];
                                if ($qtype == 0 || $qtype == 1) {
                                    $finalanswer0[$num] = $val1['option'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer0, $qtype);
                                } elseif ($qtype == 2) {
                                    $finalanswer2[] = [
                                        'okey' => $val1['okey'],
                                        'answer' => $val1['answer'],
                                    ];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer2, $qtype);
                                } elseif ($qtype == 3 || $qtype == 4 || $qtype == 5) {
                                    $finalanswer3 = $val1['answer'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer3, $qtype);
                                } elseif ($qtype == 6) {
                                    $finalanswer6[$num] = $val1['option'];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer6, $qtype);
                                } elseif ($qtype == 7 || $qtype == 8) {
                                    $finalanswer7[$num] = [
                                        'pkey' => $val1['pkey'],
                                        'problem' => $val1['problem'],
                                        'okey' => $val1['okey'],
                                    ];
                                    $formanswers[$keys][$qid] = new answers($question, $finalanswer7, $qtype);
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
                        } elseif (count($answer_ques[$keys][$qid]) < 1) {
                            $formanswers[$keys][$qid] = array();
                        }
                    }
                } elseif (count($answer_ques[$keys]) < 1) {
                    $questions = Question::getquestions($qnid);
                    if (count($questions) >= 1) {
                        foreach ($questions as $key => $val) {
                            $qtype = 0;
                            $finalanswer = [
                                'option' => '',
                            ];
                            $formanswers[$keys][$val['qid']] = new answers($val, $finalanswer, $qtype);
                        }
                    }
                }
            }
        } else {
            $formanswers = array();
        }
        $formanswers_special = array_replace_recursive($formanswers, $submit_time);
//        if ($creator_type == 0) {
//
//        } else {
//            $formanswers_pro = array_replace_recursive($stu_info, $formanswers);
//            $formanswers_special = array_replace_recursive($formanswers_pro, $submit_time);
//        }
//        $formanswers_special = array_values($formanswers_special);
//        if ($formanswers_special != null) {
//            foreach ($formanswers_special as $key => $val) {
//                $answer_final[$key] = array_values($formanswers_special[$key]);
//            }
//        }
        dd($formanswers_special);
        return view('Manager.data', [
            'questions' => $questions,
            'editors' => $editors,
            'answers' => $formanswers_special,
            'page' => $page,
        ]);
    }
}