<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Answer extends Model {
//    const arr1 = [0, 3, 4, 5];
//    const arr2 = [1, 2];
//    const arr3 = [6];
//    const arr4 = [7, 9];
//    const arr5 = [8];

    const arr1 = [0, 1, 2, 5, 6];
    const arr2 = [3, 4];
    const arr3 = [7, 8, 9];

    protected $table = 'answers';

    protected $primaryKey = 'aid';

    protected $fillable = ['answer', 'sid', 'okey', 'pkey', 'qid'];

//    public static function getAnswerType($qtype) {
//        if (in_array($qtype, self::arr1)) {
//            return 1;
//        }
//        else if (in_array($qtype, self::arr2)) {
//            return 2;
//        }
//        else if (in_array($qtype, self::arr3)) {
//            return 3;
//        }
//        else if (in_array($qtype, self::arr4)) {
//            return 4;
//        }
//        else {
//            return 5;
//        }
//    }

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

//    public static function addAnswer($data, $qnid, $sid, $qid, $atype) {
//        switch ($atype) {
//            case 1:
//                $answer = self::create([
//                    'qnid' => $qnid,
//                    'sid' => $sid,
//                    'answer' => $data,
//                    'qid' => $qid
//                ]);
//                break;
//            case 2:
//                $answer = self::create([
//                    'qnid' => $qnid,
//                    'sid' => $sid,
//                    'answer' => $data->answer,
//                    'order' => $data->order,
//                    'qid' => $qid
//                ]);
//                break;
//            case 3:
//                $answer = self::create([
//                    'qnid' => $qnid,
//                    'sid' => $sid,
//                    'answer' => $data->answer,
//                    'pkey' => $data->pkey,
//                    'qid' => $qid
//                ]);
//                break;
//        }
//        return $answer ?? null;
//    }

    public static function addAnswer($data, $qnid, $sid, $qid, $atype) {
        switch ($atype) {
            case 1:
                $answer = self::create([
                    'qnid' => $qnid,
                    'sid' => $sid,
                    'okey' => $data->okey,
                    'answer' => $data->answer,
                    'qid' => $qid
                ]);
                break;
            case 2:
                $answer = self::create([
                    'qnid' => $qnid,
                    'sid' => $sid,
                    'okey' => $data->okey,
                    'order' => $data->order,
                    'qid' => $qid
                ]);
                break;
            case 3:
                $answer = self::create([
                    'qnid' => $qnid,
                    'sid' => $sid,
                    'answer' => $data,
                    'qid' => $qid
                ]);
                break;
            case 4:
                $answer = self::create([
                    'qnid' => $qnid,
                    'sid' => $sid,
                    'pkey' => $data->pkey,
                    'answer' => $data->answer,
                    'qid' => $qid
                ]);
                break;
        }
        return $answer ?? null;
    }

//    public static function add($data, $qnid, $sid, $qid, $qtype) {
//        switch (self::getAnswerType($qtype)) {
//            case 1:
//                $answers = self::addAnswer($data, $qnid, $sid, $qid, 1);
//                break;
//            case 2:
//                for ($i = 0; $i < count($data); $i++) {
//                    $answers[$i] = self::addAnswer($data[$i], $qnid, $sid, $qid, 1);
//                }
//                break;
//            case 3:
//                for ($i = 0; $i < count($data); $i++) {
//                    $data_oneAns = [
//                        'answer' => $data[$i],
//                        'order' => $i
//                    ];
//                    $answers[$i] = self::addAnswer($data_oneAns, $qnid, $sid, $qid, 2);
//                }
//                break;
//            case 4:
//                for ($i = 0; $i < count($data); $i++) {
//                    $data_oneAns = [
//                        'answer' => $data[$i]->answer,
//                        'pkey' => $i
//                    ];
//                    $answers[$i] = self::addAnswer($data_oneAns, $qnid, $sid, $qid, 3);
//                }
//                break;
//            case 5:
//                for ($i = 0; $i < count($data); $i++) {
//                    $dataOfSinglePro = $data[$i];
//                    for ($j = 0; $j < count($dataOfSinglePro); $j++) {
//                        $data_oneAns = [
//                            'answer' => $dataOfSinglePro[$j]->answer,
//                            'pkey' => $j
//                        ];
//                        $oneAns[$i] = self::addAnswer($data_oneAns, $qnid, $sid, $qid, 3);
//                    }
//                    $answers[$i] = $oneAns ?? null;
//                }
//                break;
//        }
//        return $answers ?? null;
//    }

    public static function add($data, $qnid, $sid, $qid, $qtype) {
        switch (self::getAnswerType($qtype)) {
            case 1:
                $answers = self::addAnswer($data, $qnid, $sid, $qid, 1);
                break;
            case 2:
                for ($i = 0; $i < count($data); $i++) {
                    $answers[$i] = self::addAnswer($data[$i], $qnid, $sid, $qid, 1);
                }
                break;
            case 3:
                for ($i = 0; $i < count($data); $i++) {
                    $data_oneAns = [
                        'answer' => $data[$i],
                        'order' => $i
                    ];
                    $answers[$i] = self::addAnswer($data_oneAns, $qnid, $sid, $qid, 2);
                }
                break;
            case 4:
                for ($i = 0; $i < count($data); $i++) {
                    $data_oneAns = [
                        'answer' => $data[$i]->answer,
                        'pkey' => $i
                    ];
                    $answers[$i] = self::addAnswer($data_oneAns, $qnid, $sid, $qid, 3);
                }
                break;
            case 5:
                for ($i = 0; $i < count($data); $i++) {
                    $dataOfSinglePro = $data[$i];
                    for ($j = 0; $j < count($dataOfSinglePro); $j++) {
                        $data_oneAns = [
                            'answer' => $dataOfSinglePro[$j]->answer,
                            'pkey' => $j
                        ];
                        $oneAns[$i] = self::addAnswer($data_oneAns, $qnid, $sid, $qid, 3);
                    }
                    $answers[$i] = $oneAns ?? null;
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
            if ($select->para) {
                $query = $query->where('qid', $select->qid)
                    ->whereIn('answer', $select->options);
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
}