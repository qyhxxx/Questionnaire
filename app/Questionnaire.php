<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model {
    protected $table = 'questionnaires';

    protected $primaryKey = 'qnid';

    protected $fillable = ['user_number', 'name', 'qcount', 'status'];

    public $timestamps = true;

    public static function add($data) {
        $questionnaire = self::create($data);
        return $questionnaire;
    }

    public static function getQuestionnaire($qnid) {
        $questionnaire = self::find($qnid);
        return $questionnaire;
    }

    public static function updateByQnid($qnid, $data) {
        $questionnaire = self::find($qnid);
        $questionnaire->name = $data['name'];
        $questionnaire->qcount = $data['qcount'];
        $questionnaire->save();
        return $questionnaire;
    }

    public static function getCount($qnid) {
        $questionnaire = self::find($qnid);
        return $questionnaire->qcount;
    }
}