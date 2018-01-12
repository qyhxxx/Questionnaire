<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Answer;
use App\Usr;
use App\Helpers\forsupermanager;

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
        return $submit;
    }
}