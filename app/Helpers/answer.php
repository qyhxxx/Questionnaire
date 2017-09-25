<?php

namespace app\Helpers;

class answer{
    var $question,$option, $oneanswer, $qtype;

    public function __construct($question, $option, $oneanswer, $qtype){
        $this->question = $question;
        $this->option = $option;
        $this->answer = $oneanswer;
        $this->qtype = $qtype;
    }
}