<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submit extends Model {
    protected $table = 'submits';

    protected $primaryKey = 'sid';

    protected $fillable = ['qnid', 'user_number', 'ip', 'created_at'];

    public static function add($data) {
        $submit = self::add($data);
        return $submit;
    }

    public static function verifyRepeat($twt_name = null, $ip) {
        if ($twt_name != null) {
            $submit = self::where('twt_name', $twt_name)->get();
        }
        else {
            $submit = self::where('ip', $ip)->get();
        }
        if (empty($submit)) {
            return 1;
        }
        else {
            return 0;
        }
    }

    public static function getdata($qnid, $twt_name){
        $data = self::where(['qnid' => $qnid, 'twt_name' => $twt_name])->get();
        return $data;
    }

    public static function count_answers($qnid){
        $data = self::where('qnid',$qnid)->get();
        $count = count($data);
        return $count;
    }

    public static function answers($qnid){
        $data = self::where('qnid',$qnid)->get();
    }
}