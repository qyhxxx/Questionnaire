<?php

namespace App\Helpers;

class functions {
    public static function objectToArr($object) {
        $arr = json_decode(json_encode($object, true));
        return $arr;
    }

    public static function getIp() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDER_FOR'];
        }
        else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}