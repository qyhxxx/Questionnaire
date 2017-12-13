<?php

namespace App;

use App\Helpers\stem;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {
    const qtype_array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

    protected $table = 'questions';

    protected $primaryKey = 'qid';

    protected $fillable = ['qnid', 'qnum', 'topic', 'remark', 'qtype', 'isrequired', 'stype', 'srange', 'min', 'max', 'test'];

    public $timestamps = false;

    public function qtype($qtype = null) {
        $arr = [
            self::qtype_array[0] => '单选题',
            self::qtype_array[1] => '多选题',
            self::qtype_array[2] => '填空题',
            self::qtype_array[3] => '单行文本题',
            self::qtype_array[4] => '多行文本题',
            self::qtype_array[5] => '量表题',
            self::qtype_array[6] => '排序题',
            self::qtype_array[7] => '矩阵单选题',
            self::qtype_array[8] => '矩阵多选题',
            self::qtype_array[9] => '矩阵量表题',
            self::qtype_array[10] => '下拉题'
        ];
        if ($qtype !== null) {
            return $arr[$qtype];
        }
        return $arr;
    }

    public static function add($data) {
        $question = self::create($data);
        return $question;
    }

    public static function getQuestionByQid($qid) {
        $question = self::find($qid);
        return $question;
    }

    public static function getQuestionByQnum($qnum) {
        $question = self::where('qnum', $qnum)->first();
        return $question;
    }

    public static function getQuestionByQnum1($qnum, $qnid) {
        $question = self::where([
            'qnum' => $qnum,
            'qnid' => $qnid
        ])->first();
        return $question;
    }

    public static function getAllQuestions($qnid) {
        $data_questions = self::where('qnid', $qnid)
            ->orderBy('qnum')
            ->get();
        for ($i = 0; $i < count($data_questions); $i++) {
            $qid = $data_questions[$i]->qid;
            $qcontents = Option::getQcontentsByQid($qid);
            $questions[$i] = new \App\Helpers\question(
                $data_questions[$i], $qcontents['options'], $qcontents['problems']
            );
        }
        return $questions ?? null;
    }

    public static function deleteAll($qnid) {
        self::where('qnid', $qnid)->delete();
        return 1;
    }

    public static function getChoiceQuestions($qnid) {
        $questions = self::where('qnid', $qnid)
            ->whereIn('qtype', [0, 1])
            ->orderBy('qnum')
            ->get();
        return $questions;
    }

    public static function getonequestion($qnid,$qid){
        $data = self::where(['qnid' => $qnid,'qid' => $qid])->first();
        return $data;
    }

    //用了，别删
    public static function getquestions($qnid){
        $data = self::where('qnid',$qnid)->get();
        return $data;
    }
}