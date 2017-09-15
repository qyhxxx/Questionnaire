<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model {
    const arr1 = [0, 1, 2, 6];
    const arr2 = [7, 8];
    const arr3 = [9];

    protected $table = 'options';

    protected $primaryKey = 'oid';

    protected $fillable = ['qid', 'qnid', 'okey', 'option', 'pkey', 'problem'];

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
        $keys = array_keys($data);
        if ($isOption) {
            for ($i = 0; $i < $count; $i++) {
                $options[$i] = self::create([
                    'qid' => $qid,
                    'qnid' => $qnid,
                    'okey' => $keys[$i],
                    'option' => $data[$keys[$i]]
                ]);
            }
            return $options ?? null;
        }
        else {
            for ($i = 0; $i < $count; $i++) {
                $problems[$i] = self::create([
                    'qid' => $qid,
                    'qnid' => $qnid,
                    'pkey' => $keys[$i],
                    'problem' => $data[$keys[$i]]
                ]);
            }
            return $problems ?? null;
        }
    }

    public static function add($data_option, $data_problem = null, $qnid, $qid, $qtype) {
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
        }
        $contents = [
            'options' => $options ?? null,
            'problems' => $problems ?? null
        ];
        return $contents;
    }

    public static function getOptionsByQid($qid) {
        $options = self::where('qid', $qid)->all();
        return $options;
    }

    public static function deleteAll($qnid) {
        self::where('qnid', $qnid)->delete();
        return 1;
    }
}