<?php

namespace App\Helpers;

use App\Usr;

class login {
    public static function construct() {
        $app_key = "xVNxrf0yMuy7Cgs3pqdB";
        $app_id = "8";
        $sso = new sso($app_id, $app_key, false);
        return $sso;
    }

    public static function login($link) {
        $sso = self::construct();
        header("Location:".$sso->getLoginUrl($link));
        exit;
    }

    public static function storage($token) {
        $sso = self::construct();
        $userinfo = $sso->getUserInfo($token);
        if ($userinfo->status) {
            $result = $userinfo->result;
            $data['user_number'] = $result->user_number;
            $data['twt_name'] = $result->twt_name;
            $usr = Usr::add($data);
            $data['token'] = $token;
            session(['data' => $data]);
            return response()->json([
                'usr' => $usr,
                'success' => '登录成功'
            ]);
        }
        else {
            return back()->with('fail', '登录失败');
        }
    }
}
