<?php

namespace app\Helpers;

class forsupermanager{
    var $qid, $answer;

    public function __construct($qid, $data){
        $this->qid = $qid;
        $this->answer = $data;
    }
}