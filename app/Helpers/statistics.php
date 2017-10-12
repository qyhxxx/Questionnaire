<?php

class statistics {
    var $okey, $option, $count, $proportion;

    public function __construct($okey, $option, $count, $proportion) {
        $this->okey = $okey;
        $this->option = $option;
        $this->count = $count;
        $this->proportion = $proportion;
    }
}