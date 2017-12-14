<?php

namespace App\Helpers;

use function Sodium\library_version_major;

class functions {
    public static function objToArr($object) {
        $arr = json_decode(json_encode($object, true));
        return $arr;
    }

    public static function arrToObj($arr) {
        $obj = (object)$arr;
        return $obj;
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

    public static function numToChar($num) {
        $arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        return $arr[$num];
    }

    public static function textValidation($text, $test) {
        switch ($test) {
            case 1:
                if(!preg_match("/^1[34578]\d{9}$/", $text)) {
                    return 1;
                }
                break;
            case 2:
                if (!is_numeric($text)) {
                    return 2;
                }
                break;
            case 3:
                if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $text, $parts)) {
                    if(!checkdate($parts[2],$parts[3],$parts[1])) {
                        return 3;
                    }
                } else {
                    return 3;
                }
                break;
            case 4:
                if (!filter_var($text, FILTER_VALIDATE_EMAIL)) {
                    return 4;
                }
                break;
            case 5:
                if (!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $text)) {
                    return 5;
                }
                break;
            case 6:
                if (!preg_match("^[A-Za-z]+$", $text)) {
                    return 6;
                }
                break;
            case 7:
                $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
                if (!preg_match($regex, $text)) {
                    return 7;
                }
                break;
            case 8:
                if (!self::is_idcard($text)) {
                    return 8;
                }
            default:
        }
        return 0;
    }

    public static function is_idcard( $id )
    {
        $id = strtoupper($id);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id))
        {
            return FALSE;
        }
        if(15==strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth))
            {
                return FALSE;
            } else {
                return TRUE;
            }
        }
        else      //检查18位
        {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)) //检查生日日期是否正确
            {
                return FALSE;
            }
            else
            {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1))
                {
                    return FALSE;
                } //phpfensi.com
                else
                {
                    return TRUE;
                }
            }
        }

    }
}