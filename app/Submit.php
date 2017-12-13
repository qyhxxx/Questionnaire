<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submit extends Model {
    protected $table = 'submits';

    protected $primaryKey = 'sid';

    protected $fillable = ['qnid', 'twt_name', 'ip'];

    public $timestamps = true;

    public static function add($data) {
        $submit = self::create($data);
        return $submit;
    }

    public static function isRepeat($qnid, $twt_name = null, $ip) {
        $questionnaire = Questionnaire::getQuestionnaire($qnid);
        if ($questionnaire->onceanswer) {
            if ($twt_name) {
                $submit = self::where([
                    'qnid' => $qnid,
                    'twt_name' => $twt_name
                ])->get()->toArray();
            }
            else {
                $submit = self::where([
                    'qnid' => $qnid,
                    'ip' => $ip
                ])->get()->toArray();
            }
            if (!empty($submit)) {
                return 1;
            }
            else {
                return 0;
            }
        }
        return 0;
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
}