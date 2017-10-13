<?php

namespace App\Helpers;

class statistics {
    var $okey, $option, $count, $sum, $proportion;

    public function __construct($okey, $option, $count, $sum, $proportion) {
        $this->okey = $okey;
        $this->option = $option;
        $this->count = $count;
        $this->sum = $sum;
        $this->proportion = $proportion;
    }
}