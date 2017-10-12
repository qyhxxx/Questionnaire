<?php

namespace App\Helpers;

class question {
    var $question, $answer, $options, $problems;

    public function __construct($data_question, $data_options = null, $data_problems = null) {
        $this->question = $data_question;
        $this->answer = null;
        $this->options = $data_options;
        foreach ($data_problems as $item) {
            $item->answer = array();
        }
        $this->problems = $data_problems;
    }
}