<?php

namespace app\Http\Controllers\Admin;

use App\Answer;
use App\Option;
use App\Question;
use App\Questionnaire;
use App\Submit;

class QuestionnaireManagementController {
    public function listOfQuestionnaires() {
        $questionnaires = Questionnaire::getAllQuestionnaires();
        return view('Questionnaire.list', ['questionnaires' => $questionnaires]);
    }

    public function deletedList() {
        $questionnaires = Questionnaire::getDeletedList();
        return view('Questionnaire.deletedList', ['questionnaires' => $questionnaires]);
    }

    public function check($qnid) {
        header("Location:https://survey.twtstudio.com/answer/".$qnid);
        exit;
    }

    public function softDelete($qnid) {
        Questionnaire::softDeleteByQnid($qnid);
        return redirect('admin/questionnaire/list');
    }

    public function restore($qnid) {
        Questionnaire::restore($qnid);
        return redirect('admin/questionnaire/deletedList');
    }

    public function forceDelete($qnid) {
        Questionnaire::forceDeleteByQnid($qnid);
        return 1;
    }
}