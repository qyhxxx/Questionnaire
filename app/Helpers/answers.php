<?php

namespace app\Helpers;

class answers{
    var $qid, $answer, $qtype;

    public function __construct($question, $finalanswer, $qtype){

        $this->qid = $question->qid;
        $this->answer = $finalanswer;
        $this->qtype = $qtype;
    }
}