<?php

namespace App;

use App\Helpers\functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model {
    use SoftDeletes;

    protected $table = 'questionnaires';

    protected $primaryKey = 'qnid';

    protected $fillable = ['twt_name', 'name', 'remark', 'qcount', 'status', 'hasnumber', 'recover_at', 'ischecked',
            'onceanswer', 'num', 'eid', 'recovery', 'issetddl', 'verifiedphone', 'created_at', 'updated_at', 'ishidden'];

    public $timestamps = false;

    protected $dates = ['delete_at'];

    public static function add($data) {
        $questionnaire = self::create($data);
        return $questionnaire;
    }

    public static function updateByQnid($qnid, $data) {
        $questionnaire = self::getQuestionnaire($qnid);
        $questionnaire->update($data);
        return $questionnaire;
    }

    public static function getQuestionnaire($qnid) {
        $questionnaire = self::find($qnid);
        return $questionnaire;
    }

    public static function getCount($qnid) {
        $questionnaire = self::find($qnid);
        return $questionnaire->qcount;
    }

    public static function getdata($qnid){
        $data = self::where('qnid',$qnid)->first();
        return $data;
    }

    public static function update_install($qnid, $install){
        $questionnaire = self::find($qnid);
        if($install['hasnumber'] != $questionnaire->hasnumber){
            $questionnaire->hasnumber = $install['hasnumber'];
        }
        if($install['recovery_at'] != ''){
            $questionnaire->recovery_at = $install['recovery_at'];
        }
        if($install['ischecked'] != $questionnaire->ischecked){
            $questionnaire->ischecked = $install['ischecked'];
        }
        if($install['onceanswer'] != $questionnaire->onceanswer){
            $questionnaire->onceanswer = $install['onceanswer'];
        }
        if($install['issetddl'] != $questionnaire->issetddl){
            $questionnaire->issetddl = $install['issetddl'];
        }
        if ($install['verifiedphone'] != $questionnaire->verifiedphone){
            $questionnaire->verifiedphone = $install['verifiedphone'];
        }
        $questionnaire->save();
        return $questionnaire;
    }

    public static function update_collect($qnid, $install){
        $questionnaire = self::find($qnid);
        if($install['status'] != $questionnaire->status){
            $questionnaire->status = $install['status'];
        }
        $questionnaire->save();
        return $questionnaire;
    }

    //缩略图页面搜索相应问卷
    public static function reach($data){
        $reach = self::where('name','like','%'.$data.'%')->get();
        return $reach;
    }

    //缩略图页面搜索相应问卷
    public static function sequence($order_status = null,$order_sequence,$id){
        if($order_status == null){
            if($order_sequence==1) {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where('qnid', $val)
                        ->orderBy('created_at','desc')
                        ->get();
                }
            }
            else {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where('qnid', $val)
                        ->orderBy('created_at','asc')
                        ->get();
                }
            }
            return $questionnaire ?? null;
        }
        elseif ($order_status ==0){
            if($order_sequence==1) {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where([
                        'status' => 0,
                        'qnid' => $val,
                    ])
                        ->orderBy('created_at','desc')
                        ->get();
                }
            }
            else {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where([
                        'status' => 0,
                        'qnid' => $val,
                    ])
                        ->orderBy('created_at','asc')
                        ->get();
                }
            }
            return $questionnaire ?? null;
        }
        elseif ($order_status == 1){
            if($order_sequence==1) {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where([
                        'status' => 1,
                        'qnid' =>$val,
                    ])
                        ->orderBy('created_at','desc')
                        ->get();
                }
            }
            else {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where([
                        'status' => 1,
                        'qnid' => $id,
                    ])
                        ->orderBy('created_at','asc')
                        ->get();
                }
            }
            return $questionnaire ?? null;
        }
        else{
            if($order_sequence==1) {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where([
                        'status' => 2,
                        'qnid' => $id,
                    ])
                        ->orderBy('created_at','desc')
                        ->get();
                }
            }
            else {
                foreach ($id as $key=>$val){
                    $questionnaire[$key] = self::where([
                        'status' => 2,
                        'qnid' => $id,
                    ])
                        ->orderBy('created_at','asc')
                        ->get();
                }
            }
            return $questionnaire ?? null;
        }
    }

    //浏览量统计。
    public static function visitor_volume($qnid){
        $hasvisited = session()->get('hasvisited');
        if(!isset($hasvisited)) {
            session(['hasvisited' => 1]);
            self::where('qnid', $qnid)->increment('num');
        }
    }

    public static function getQuestionnaires($qnid){
        $questionnaires = self::where('qnid', $qnid)->first();
        return $questionnaires;
    }

    public static function getQuestionnaireByname($twt_name){
        $questionnaires = self::where('twt_name', $twt_name)->get();
        return $questionnaires;
    }

    public static function getAllQuestionnaires() {
        $questionnaires = self::paginate(15);
        return $questionnaires;
    }

    public static function getDeletedList() {
        $questionnaires = self::onlyTrashed()->paginate(15);
        return $questionnaires;
    }

    public static function getDeletedQuestionnaires() {
        $questionnaires = self::onlyTrashed()->paginate(15);
        return $questionnaires;
    }

    public static function forceDeleteByQnid($qnid) {
        self::where('qnid', $qnid)->forceDelete();
    }

    public static function restore($qnid) {
        self::where('qnid', $qnid)->restore();
    }

    public static function softDeleteByQnid($qnid) {
        self::where('qnid', $qnid)->delete();
    }

    public static function deleteQuestionnaire($qnid){
        $questionnaire = self::where('qnid', $qnid)->delete();
        $answers = Answer::deleteByQnid($qnid);
        $submit = Submit::deleteByQnid($qnid);
        $editor = Editor::deleteByQnid($qnid);
        $option = Option::deleteByQnid($qnid);
        $question = Question::deleteByQnid($qnid);
    }
}