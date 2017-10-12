<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model {
    const arr1 = [0];
    const arr2 = [1, 6, 7, 9];
    const arr3 = [3, 4, 5];
    const arr4 = [8];

    protected $table = 'answers';

    protected $primaryKey = 'aid';

    protected $fillable = ['sid', 'qnid', 'qid', 'answer', 'okey', 'option', 'pkey', 'problem'];

    public $timestamps = false;

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
        else {
            return 4;
        }
    }

    public static function add($data, $sid) {
        $qid = $data['qid'];
        $submit_answer = $data['answer'];
        $qtype = Question::getQuestionByQid($qid)->qtype;
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
        }
//        if ($qtype == 0) {
//            $data['sid'] = $sid;
//            $okey = $submit_answer;
//            $data['okey'] = $okey;
//            $data['option'] = Option::getOption($qid, $okey);
//            $answer = self::create($data);
//        }
//        else if ($qtype == 1 || $qtype == 6 || $qtype == 7 || $qtype == 9) {
//            for ($i = 0; $i < count($submit_answer); $i++) {
//                $data['sid'] = $sid;
//                if ($qtype != 9) {
//                    $okey = $submit_answer[$i];
//                    $data['okey'] = $okey;
//                    $data['option'] = Option::getOption($qid, $okey);
//                }
//                if ($qtype == 6) {
//                    $data['answer'] = $i + 1;
//                }
//                if ($qtype == 7 || $qtype == 9) {
//                    $pkey = $i + 1;
//                    $data['pkey'] = $pkey;
//                    $data['problem'] = Option::getProblem($qid, $pkey);
//                    if ($qtype == 9) {
//                        $data['answer'] = $submit_answer[$i];
//                    }
//                }
//                $answer[$i] = self::create($data);
//            }
//        }
//        else if ($qtype == 3 || $qtype == 4 || $qtype == 5) {
//            $data['sid'] = $sid;
//            $answer = self::create($data);
//        }
//        else {
//            for ($i = 0; $i < count($submit_answer); $i++) {
//                for ($j = 0; $j < count($submit_answer[$i]); $j++) {
//                    $data['sid'] = $sid;
//                    $okey = $submit_answer[$i][$j];
//                    $data['okey'] = $okey;
//                    $data['option'] = Option::getOption($qid, $okey);
//                    $pkey = $i + 1;
//                    $data['pkey'] = $pkey;
//                    $data['problem'] = Option::getProblem($qid, $pkey);
//                    $answerOfOnePro[$j] = self::create($data);
//                }
//                $answer[$i] = $answerOfOnePro ?? null;
//            }
//        }
        return $answer ?? null;
    }

    public static function getAnswers($sid, $qid) {
        $answers = self::where([
            'sid' => $sid,
            'qid' => $qid
        ])->get();
        return $answers;
    }

    public static function getSidArr($requirements) {
        if ($requirements) {
            $query = new Answer();
            foreach ($requirements as $requirement){
                if ($requirement['para']) {
                    $query = $query->where('qid', $requirement['qid'])
                        ->whereIn('okey', $requirement['okey']);
                }
                else {
                    $query = $query->where('qid', $requirement['qid'])
                        ->whereNotIn('okey', $requirement['okey']);
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
            $sidArr = Submit::getSidArr();
        }
        return $sidArr;
    }

    public static function statistics($sidArr, $qid, $okey) {
        $answer = self::whereIn('sid', $sidArr)
            ->where([
                'qid' => $qid,
                'okey' => $okey
            ])->get();
        $count = count($answer);
        $proportion = doubleval($count / count($sidArr));
        return [
            'count' => $count,
            'proportion' => $proportion
        ];
    }

    public static function getdata($qid){
        $data = self::where('qid', $qid)->get();
        return $data;
    }

    public static function partkilled($aid){
        $partkilled = self::where('aid', $aid)->delete();
        return $partkilled;
    }

    public static function allkilled(){
        $allkilled = self::delete();
        return $allkilled;
    }


    public static function getmanyanswers($qnid){
        $answers = self::where('qnid', $qnid)->get()->toArray();
        return $answers;
    }

    public static function getoneanswer($sid, $qid){
        $answer = self::where(['sid'=>$sid, 'qid'=>$qid]);
        return $answer;
    }
}