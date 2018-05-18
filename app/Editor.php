<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Editor extends Model{

    protected $table = 'editors';

    protected $primaryKey = 'id';

    protected $fillable = ['qnid', 'twt_name'];

    public $timestamps = false;

    public static function add($twt_name, $qnid){
        $editor=self::create([
            'twt_name' => $twt_name,
            'qnid' => $qnid,
        ]);
        return $editor;
    }

    public static function questionnaires($twt_name){
        $questionnaires = self::where('twt_name', $twt_name)->get();
        return $questionnaires;
    }

    public static function getdata($qnid){
        $data = self::where('qnid', $qnid)->get();
        return $data;
    }

    public static function getQnid($twt_name){
        $data = self::where('twt_name', $twt_name)->get();
        for ($i = 0; $i < count($data); $i++) {
            $qnid[$i] = $data[$i]->qnid;
        }
        return $qnid ?? null;
    }

    public static function hasPower($qnid, $twt_name) {
        $editors = self::where([
            'qnid' => $qnid,
            'twt_name' => $twt_name
        ])->first();
        if ($editors != null) {
            return 1;
        }
        else {
            return 0;
        }
    }

    public static function deleteByQnid($qnid){
        self::where('qnid', $qnid)->delete();
    }
}