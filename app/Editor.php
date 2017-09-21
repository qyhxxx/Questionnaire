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

}