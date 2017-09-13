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

    protected $fillable = ['answer', 'order', 'okey', 'pkey', 'qid'];

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

    public static function addAnswer($data, $qid, $atype) {
        switch ($atype) {
            case 1:
                $answer = self::create([
                    'answer' => $data,
                    'qid' => $qid
                ]);
                break;
            case 2:
                $answer = self::create([
                    'answer' => $data->answer,
                    'okey' => $data->okey,
                    'qid' => $qid
                ]);
                break;
            case 3:
                $answer = self::create([
                    'answer' => $data->answer,
                    'order' => $data->order,
                    'qid' => $qid
                ]);
                break;
            case 4:
                $answer = self::create([
                    'answer' => $data->answer,
                    'pkey' => $data->pkey,
                    'qid' => $qid
                ]);
                break;
        }
        return $answer ?? null;
    }

    public static function add($data, $qnid) {
        for ($i = 0; $i < count($data); $i++) {
            $qnum = $i + 1;
            $question = Question::getQuestionByQnum($qnum);
            $qid = $question->qid;
            $qtype = $question->qtype;
            $data_answer = $data[$i];
            switch (self::getAnswerType($qtype)) {
                case 1:
                    $answer[$i] = self::addAnswer($data_answer, $qid, 1);
                    break;
                case 2:
                    for ($j = 0; $j < count($data_answer); $j++) {
                        $oneAns[$j] = self::addAnswer($data_answer[$j], $qid, 1);
                    }
                    $answer[$i] = $oneAns ?? null;
                    break;
                case 3:
                    for ($j = 0; $j < count($data_answer); $j++) {
                        $data_oneAns = [
                            'okey' => $j,
                            'answer' => $data_answer[$j]
                        ];
                        $oneAns[$j] = self::addAnswer($data_oneAns, $qid, 2);
                    }
                    $answer[$i] = $oneAns ?? null;
                    break;
                case 4:
                    for ($j = 0; $j < count($data_answer); $j++) {
                        $data_oneAns = [
                            'answer' => $data_answer[$j],
                            'order' => $j
                        ];
                        $oneAns[$j] = self::addAnswer($data_oneAns, $qid, 3);
                    }
                    $answer[$i] = $oneAns ?? null;
                    break;
                case 5:
                    for ($j = 0; $j < count($data_answer); $j++) {
                        $data_oneAns = [
                            'pkey' => $j,
                            'answer' => $data_answer[$j]
                        ];
                        $oneAns = self::addAnswer($data_oneAns, $qid, 4);
                    }
                    $answer[$i] = $oneAns ?? null;
                    break;
                case 6:
                    for ($j = 0; $j < count($data_answer); $j++) {
                        $dataOfSinglePro = $data_answer[$j];
                        for ($k = 0; $k < count($dataOfSinglePro); $k++) {
                            $data_oneAns = [
                                'answer' => $dataOfSinglePro[$k],
                                'pkey' => $j
                            ];
                            $oneAns[$j] = self::addAnswer($data_oneAns, $qid, 4);
                        }
                        $ansOfSinglePro[$j] = $oneAns ?? null;
                    }
                    $answer[$i] = $ansOfSinglePro ?? null;
                    break;
            }
        }
        return $answer ?? null;
    }
}