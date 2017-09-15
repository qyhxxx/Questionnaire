<?php

namespace App\Helpers;

class stem {
    var $qnum, $qid, $topic;

    public function __construct($qnum, $qid, $topic) {
        $this->qnum = $qnum;
        $this->qid = $qid;
        $this->topic = $topic;
    }
}