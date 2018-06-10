<?php

namespace App;

use App\Helpers\functions;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model {
    const arr1 = [0];
    const arr2 = [1, 6, 7, 9];
    const arr3 = [3, 4, 5];
    const arr4 = [8];

    protected $table = 'answers';

    protected $primaryKey = 'aid';

    protected $fillable = ['sid', 'qnid', 'qid', 'answer', 'okey', 'option', 'pkey', 'problem'];

    public $timestamps = true;

    public static function getAnswerType($qtype) {
        if (in_array($qtype, self::arr1)) {
            return 1;
        }
        else if (in_array($qtype, self::arr2)) {
            return 2;
        }
        else if (in_array($qtype, self::arr3)) {
            return 3;
        }
        else if (in_array($qtype, self::arr4)) {
            return 4;
        } else {
            return 5;
        }
    }

    public static function add($data, $sid, $qnid, $i) {
        if ($data == null) {
            $question = Question::getQuestionByQnum1($i + 1, $qnid);
            $qtype = $question->qtype;
            $qid = $question->qid;
            if ($qtype == 6) {
                $options = Option::getOptionsByQid($qid);
                for ($i = 0; $i < count($options); $i++) {
                    $data['qnid'] = $qnid;
                    $data['qid'] = $qid;
                    $data['sid'] = $sid;
                    $okey = functions::numToChar($i);
                    $data['okey'] = $okey;
                    $data['option'] = Option::getOption($qid, $okey);
                    $answers[$i] = self::create($data);
                }
                return $answers ?? null;
            } else if ($question->isrequired) {
                return -1;
            } else {
                return 0;
            }
        }
        $qid = $data['qid'];
        $submit_answer = $data['answer'];
        $question = Question::getQuestionByQid($qid);
        $qtype = $question->qtype;
        $min = $question->min;
        $max = $question->max;
        if ($question->isrequired){
            if ($qtype == 7 || $qtype == 8 || $qtype == 9) {
                $num = count($data['answer']);
                $problem_num = Option::countProblemByQid($qid);
                if($num != $problem_num){
                    return -1;
                }
            }
//            if ($qtype == 8) {
//
////                if ($min != null && $max != null) {
//////                    for ($i = 0; $i < count($data['answer']); $i++) {
//////                        $num = count($data['answer'][$i]);
//////                        if ($num < $min || $num > $max) {
//////                            return -1;
//////                        }
//////                    }
////                    $i = $min;
////                }
//            }
        }
        if (self::getAnswerType($qtype) != 3) {
            unset($data['answer']);
        }
        switch (self::getAnswerType($qtype)) {
            case 1:
                $data['sid'] = $sid;
                $okey = $submit_answer;
                $data['okey'] = $okey;
                $data['option'] = Option::getOption($qid, $okey);
                $answer = self::create($data);
                break;
            case 2:
                for ($i = 0; $i < count($submit_answer); $i++) {
                    $data['sid'] = $sid;
                    if ($qtype != 9) {
                        $okey = $submit_answer[$i];
                        $data['okey'] = $okey;
                        $data['option'] = Option::getOption($qid, $okey);
                    }
                    if ($qtype == 6) {
                        $data['answer'] = $i + 1;
                    }
                    if ($qtype == 7 || $qtype == 9) {
                        $pkey = $i + 1;
                        $data['pkey'] = $pkey;
                        $data['problem'] = Option::getProblem($qid, $pkey);
                        if ($qtype == 9) {
                            $data['answer'] = $submit_answer[$i];
                        }
                    }
                    $answer[$i] = self::create($data);
                }
                break;
            case 3:
                $data['sid'] = $sid;
                $test = $question->test;
                $error = functions::textValidation($submit_answer, $test);
                if ($error != 0) {
                    return $error;
                }
                $answer = self::create($data);
                break;
            case 4:
                for ($i = 0; $i < count($submit_answer); $i++) {
                    for ($j = 0; $j < count($submit_answer[$i]); $j++) {
                        $data['sid'] = $sid;
                        $okey = $submit_answer[$i][$j];
                        $data['okey'] = $okey;
                        $data['option'] = Option::getOption($qid, $okey);
                        $pkey = $i + 1;
                        $data['pkey'] = $pkey;
                        $data['problem'] = Option::getProblem($qid, $pkey);
                        $answerOfOnePro[$j] = self::create($data);
                    }
                    $answer[$i] = $answerOfOnePro ?? null;
                }
                break;
            default:
                $data['sid'] = $sid;
                $data['okey'] = 'st';
                $data['option'] = $submit_answer['st'];
                $answer['st'] = self::create($data);
                $data['okey'] = 'nd';
                $data['option'] = $submit_answer['nd'];
                $answer['nd'] = self::create($data);
                break;
        }
        return $answer ?? null;
    }

    public static function getAnswers($sid, $qid) {
        $answers = self::where([
            'sid' => $sid,
            'qid' => $qid
        ])->get();
        return $answers;
    }

    public static function getSidArr($requirements, $qnid) {
        if ($requirements != null) {
            $query = new Answer();
            foreach ($requirements as $requirement){
                $qid = isset($requirement['okey']) ? $requirement['qid'] : null;
                $para = isset($requirement['para']) ? $requirement['para'] : null;
                $okey = isset($requirement['okey']) ? $requirement['okey'] : null;
                if ($qid == null || $para == null || $okey == null) {
                    if (count($requirements) == 1) {
                        $sidArr = Submit::getSidArr($qnid);
                        return $sidArr;
                    }
                    continue;
                }
                if ($para) {
                    $query = $query->where('qid', $qid)
                        ->whereIn('okey', $okey);
                }
                else {
                    $query = $query->where('qid', $qid)
                        ->whereNotIn('okey', $okey);
                }
            }
            $answers = $query->get();
            $sidArr = array();
            for ($i = 0; $i < count($answers); $i++) {
                $sidArr[$i] = $answers[$i]->sid;
            }
            $sidArr = array_unique($sidArr);
        }
        else {
            $sidArr = Submit::getSidArr($qnid);
        }
        return $sidArr;
    }

    public static function statistics($sidArr, $qid, $okey) {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
        $answer = self::whereIn('sid', $sidArr)
            ->where([
                'qid' => $qid,
                'okey' => $okey
            ])->get();
        $count = count($answer);
        $sum = count($sidArr);
        $proportion = doubleval($count / $sum);
        return [
            'count' => $count,
            'sum' => $sum,
            'proportion' => $proportion
        ];
    }


    //gh
    public static function getdata($qid){
        $data = self::where('qid', $qid)->get();
        return $data;
    }

    public static function partkilled($sid){
        $partkilled = self::where('sid', $sid)->update(['ishidden' => 1]);
        return $partkilled;
    }

    public static function allkilled($qnid){
        $allkilled = self::where('qnid', $qnid)->delete();
        return $allkilled;
    }


    public static function getmanyanswers($qnid){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time','0');
        $answers = self::where('qnid', $qnid)->get();
        return $answers;
    }

    public static function getoneanswer($sid, $qid){
        $answer = self::where(['sid'=>$sid, 'qid'=>$qid]);
        return $answer;
    }

    public static function getAnswerBySid($sid){
        $answer = self::where('sid', $sid)->get();
        return $answer;
    }

    public static function deleteBySid($sid) {
        self::where('sid', $sid)->delete();
    }

    public static function deleteByQnid($qnid){
        self::where('qnid', $qnid)->delete();
    }

    public static function getAnswersQQ($sid) {
        $answers = self::where('sid', $sid)->get();
        for ($i = 0; $i < count($answers); $i++) {
            $qid = $answers[$i]->qid;
            $formAnswer[$qid] = $answers[$i];
        }
        return $formAnswer ?? null;
    }

}
