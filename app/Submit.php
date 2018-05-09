<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Answer;
use App\Usr;
use App\Helpers\forsupermanager;
use App\Helpers\answers;

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
        $submits = self::where('qnid', $qnid)->pluck('sid');
        return $submits;
    }

    public static function getdata($qnid, $twt_name){
        $data = self::where(['qnid' => $qnid, 'twt_name' => $twt_name])->get();
        return $data;
    }

    //用了别删
    public static function count_answers($qnid){
        $data = self::where('qnid',$qnid)->count();
        return $data;
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

    public static function copeSubmit($qnid, $page){
        $submit = self::where('qnid', $qnid)->forPage($page, 15)->get();
        return $submit;
    }

    public static function getAllSubmit($qnid){
        $submit = self::where('qnid', $qnid)->paginate(20);
        return $submit;
    }
}