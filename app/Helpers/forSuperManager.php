<?php

namespace app\Helpers;

class forSuperManager{
    var $qid, $answer;

    public function __construct($qid, $data){
        $this->qid = $qid;
        $this->answer = $data;
    }
}