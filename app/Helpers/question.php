<?php

namespace App\Helpers;

class question {
    var $question, $answer, $options, $problems;

    public function __construct($data_question, $data_options = null, $data_problems = null) {
        $this->question = $data_question;
        $qtype = $data_question->qtype;
        if ($qtype == 10) {
            $this->answer = ['st' => '', 'nd' => ''];
        } else if ($qtype == 1 || $qtype == 6 || $qtype == 8 || $qtype == 9) {
            $this->answer = array();
        } else if ($qtype == 7) {
            $this->answer = array(array());
        } else{
            $this->answer = '';
        }
        $this->options = $data_options;
        foreach ($data_problems as $item) {
            $item->answer = array();
        }
        $this->problems = $data_problems;
    }
}