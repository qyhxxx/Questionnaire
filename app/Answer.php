<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Answer extends Model {
    const arr1 = [0, 3, 4, 5];
    const arr2 = [1];
    const arr3 = [2];
    const arr4 = [6];
    const arr5 = [7, 9];
    const arr6 = [8];

    protected $table = 'answers';

    protected $primaryKey = 'aid';

    protected $fillable = ['answer', 'sid', 'okey', 'pkey', 'qid'];

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
        }
        else if (in_array($qtype, self::arr5)) {
            return 5;
        }
        else {
            return 6;
        }
    }

    public static function addAnswer($data, $sid, $qid, $atype) {
        switch ($atype) {
            case 1:
                $answer = self::create([
                    'sid' => $sid,
                    'answer' => $data,
                    'qid' => $qid
                ]);
                break;
            case 2:
                $answer = self::create([
                    'sid' => $sid,
                    'answer' => $data->answer,
                    'okey' => $data->okey,
                    'qid' => $qid
                ]);
                break;
            case 3:
                $answer = self::create([
                    'sid' => $sid,
                    'answer' => $data->answer,
                    'order' => $data->order,
                    'qid' => $qid
                ]);
                break;
            case 4:
                $answer = self::create([
                    'sid' => $sid,
                    'answer' => $data->answer,
                    'pkey' => $data->pkey,
                    'qid' => $qid
                ]);
                break;
        }
        return $answer ?? null;
    }

    public static function add($data, $sid, $qid, $qtype) {
        switch (self::getAnswerType($qtype)) {
            case 1:
                $answers = self::addAnswer($data, $sid, $qid, 1);
                break;
            case 2:
                for ($i = 0; $i < count($data); $i++) {
                    $answers[$i] = self::addAnswer($data[$i], $sid, $qid, 1);
                }
                break;
            case 3:
                for ($i = 0; $i < count($data); $i++) {
                    $data_oneAns = [
                        'okey' => $data[$i]->okey,
                        'answer' => $data[$i]->answer
                    ];
                    $answers[$i] = self::addAnswer($data_oneAns, $sid, $qid, 2);
                }
                break;
            case 4:
                for ($i = 0; $i < count($data); $j++) {
                    $data_oneAns = [
                        'order' => $i,
                        'answer' => $data[$i]
                    ];
                    $answers[$i] = self::addAnswer($data_oneAns, $sid, $qid, 3);
                }
                break;
            case 5:
                for ($i = 0; $i < count($data); $i++) {
                    $data_oneAns = [
                        'pkey' => $data[$i]->pkey,
                        'answer' => $data[$i]->answer
                    ];
                    $answers[$i] = self::addAnswer($data_oneAns, $sid, $qid, 4);
                }
                break;
            case 6:
                for ($i = 0; $i < count($data); $i++) {
                    $dataOfSinglePro = $data[$i];
                    for ($j = 0; $j < count($dataOfSinglePro); $j++) {
                        $data_oneAns = [
                            'answer' => $dataOfSinglePro[$j]->answer,
                            'pkey' => $dataOfSinglePro[$j]->pkey
                        ];
                        $oneAns[$i] = self::addAnswer($data_oneAns, $sid, $qid, 4);
                    }
                    $answers[$i] = $oneAns ?? null;
                }
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

    public static function getSubmits($selects) {
        $query = new Answer();
        foreach ($selects as $select){
            if ($select->para) {
                $query = $query->where('qid', $select['qid'])
                    ->whereIn('answer', $select['options']);
            }
        }
        $answers = $query->get();
        for ($i = 0; $i < count($answers); $i++) {
            $sidArr[$i] = $answers[$i]->sid;
        }
        $submits = Submit::getSubmitsBySidArr($sidArr ?? null);
        return $submits;
    }

    public static function statistics($sid, $okey) {
        $options = self::where([
            'sid' => $sid,
            'answer' => $okey
        ])->get();
        $count = count($options);
        return $count;
    }
}