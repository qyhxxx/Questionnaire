<?php

namespace App;

use App\Helpers\functions;
use Illuminate\Database\Eloquent\Model;

class Option extends Model {
    const arr1 = [0, 1, 2, 6, 10];
    const arr2 = [7, 8];
    const arr3 = [9];

    protected $table = 'options';

    protected $primaryKey = 'oid';

    protected $fillable = ['qid', 'qnid', 'okey', 'option', 'test', 'pkey', 'problem'];
//    protected $fillable = ['qid', 'qnid', 'okey', 'option', 'test', 'pkey', 'problem', 'remark'];

    public $timestamps = false;

    public static function getFormType($qtype) {
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

    public static function getQcontentsByQid($qid) {
        $options = self::where('qid', $qid)
            ->where('okey', '!=', null)
            ->get();
        $problems = self::where('qid', $qid)
            ->where('pkey','!=',null)
            ->get();
        return ['options' => $options, 'problems' => $problems];
    }

    public static function batchAdd($data, $qnid, $qid, $isOption) {
        $count = count($data);
        if ($isOption) {
            for ($i = 0; $i < $count; $i++) {
                $options[$i] = self::create([
                    'qid' => $qid,
                    'qnid' => $qnid,
                    'okey' => $count > 26 ? ($i + 1) : functions::numToChar($i),
                    'option' => $data[$i]['option'],
                    'test' => $data[$i]['test'] ?? null,
               //     'remark' => $data[$i]['remark'] ?? null,
                ]);
            }
            return $options ?? null;
        }
        else {
            for ($i = 0; $i < $count; $i++) {
                $problems[$i] = self::create([
                    'qid' => $qid,
                    'qnid' => $qnid,
                    'pkey' => $i + 1,
                    'problem' => $data[$i]['problem']
                ]);
            }
            return $problems ?? null;
        }
    }

    public static function add($data_option, $data_problem, $qnid, $qid, $qtype) {
        switch (self::getFormType($qtype)) {
            case 1:
                $options = self::batchAdd($data_option, $qnid, $qid, 1);
                break;
            case 2:
                $options = self::batchAdd($data_option, $qnid, $qid, 1);
                $problems = self::batchAdd($data_problem, $qnid, $qid, 0);
                break;
            case 3:
                $problems = self::batchAdd($data_problem, $qnid, $qid, 0);
                break;
            default:

        }
        $contents = [
            'options' => $options ?? null,
            'problems' => $problems ?? null
        ];
        return $contents;
    }

    public static function getOptionsByQid($qid) {
        $options = self::where('qid', $qid)->get();
        return $options;
    }

    public static function deleteAll($qnid) {
        self::where('qnid', $qnid)->delete();
        return 1;
    }

    public static function deleteByQnid($qnid){
        self::where('qnid', $qnid)->delete();
    }

    public static function getOption($qid, $okey) {
        $option = self::where([
            'qid' => $qid,
            'okey' => $okey
        ])->first()->option;
        return $option;
    }

    public static function getProblem($qid, $pkey) {
        $problem = self::where([
            'qid' => $qid,
            'pkey' => $pkey
        ])->first()->problem;
        return $problem;
    }

    public static function countProblemByQid($qid){
        $problem = self::where('qid', $qid)->get();
        $num = 0;
        foreach ($problem as $val){
            if($val->problem != null){
                $num++;
            }
        }
        return $num;
    }
}