<?php

namespace App\Helpers;

class question {
    var $question, $options, $problems;

    public function __construct($data_question, $data_options = null, $data_problem = null) {
        $this->question = $data_question;
        $this->options = $data_options;
        $this->problems = $data_problem;
    }
}