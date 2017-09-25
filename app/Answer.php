<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Answer extends Model {
    const arr1 = [0, 3, 4, 5];
    const arr2 = [1, 2, 6, 7, 8];
    const arr4 = [9];

    protected $table = 'answers';

    protected $primaryKey = 'aid';

    protected $fillable = ['sid', 'qnid', 'qid', 'answer', 'okey', 'option', 'pkey', 'problem'];

    public static function getAnswerType($qtype) {
        if (in_array($qtype, self::arr1)) {
            return 1;
        }
        else if (in_array($qtype, self::arr2)) {
            return 2;
        }
        else {
            return 3;
        }
    }

    public function batchAdd($data, $info) {
        $data['sid'] = $info['sid'];
        $data['qnid'] = $info['qnid'];
        $data['qid'] = $info['qid'];
        $data['pkey'] = $info['pkey'] ?? null;
        $data['problem'] = $info['problem'] ?? null;
        return $data;
    }

    public static function add($data, $info, $qtype) {
        switch (self::getAnswerType($qtype)) {
            case 1:
                $data = self::batchAdd($data, $info);
                $answers = self::create($data);
                break;
            case 2:
                for ($i = 0; $i < count($data); $i++) {
                    $data[$i] = self::batchAdd($data[$i], $info);
                    $answers[$i] = self::create($data[$i]);
                }
                break;
            case 3:
                for ($i = 0; $i < count($data); $i++) {
                    $info['pkey'] = $data[$i]['pkey'];
                    $info['problem'] = $data[$i]['problem'];
                    $options = $data[$i]['options'];
                    for ($j = 0; $j < count($options); $j++) {
                        $options[$j] = self::batchAdd($options[$j], $info);
                    }
                    $answers[$i] = $options;
                }
                break;
        }
        return $answers ?? null;
    }

    public static function getAnswers($sid, $qid) {
        $answers = self::where([
            'sid' => $sid,
            'qid' => $qid
        ])->get();
        return $answers;
    }

    public static function getSidArr($selects) {
        $query = new Answer();
        foreach ($selects as $select){
            if ($select['pare']) {
                $query = $query->where('qid', $select['question']['qid'])
                    ->whereIn('okey', $select['options']['okey']);
            }
        }
        $answers = $query->get();
        for ($i = 0; $i < count($answers); $i++) {
            $sidArr[$i] = $answers[$i]->sid;
        }
        $sidArr = array_unique($sidArr ?? null);
        return $sidArr;
    }

    public static function statistics($sidArr, $qid, $okey) {
        $answer = self::whereIn('sid', $sidArr)
            ->where([
                'qid' => $qid,
                'okey' => $okey
            ])->get();
        $count = count($answer);
        return $count;
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