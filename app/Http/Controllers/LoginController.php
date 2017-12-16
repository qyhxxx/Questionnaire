<?php

namespace App\Http\Controllers;

use App\Helpers\functions;
use App\Helpers\sso;
use App\Usr;
use Illuminate\Http\Request;
use TwT\SSO\Api;

class LoginController extends Controller {
    public static function construct() {
        $app_key = "xVNxrf0yMuy7Cgs3pqdB";
        $app_id = "8";
        $sso = new Api($app_id, $app_key);
        return $sso;
    }

    public static function login() {
        $sso = self::construct();
        $link = "http://survey.twtstudio.com/";
        header("Location:".$sso->getLoginUrl($link));
        exit;
    }

    public static function storage($token) {
        $sso = self::construct();
        $userinfo = $sso->fetchUserInfo($token);
        if ($userinfo->status == 1) {
            $result = $userinfo->result;
            echo $result->user_number . "1 <br>";
            $data['user_number'] = $result->user_number;
            $data['twt_name'] = $result->twt_name;
            echo $result->user_number . "2 <br>";
            Usr::add($data);
            $data['type'] = 0;
            $data['token'] = $token;
            echo $result->user_number . "3 <br>";
            session(['data' => $data]);
            echo $result->user_number . "4 <br>";
            exit;
            return 1;
        }
        else {
            return 0;
        }
    }

    public function loginStatus(Request $request) {
        if ($request->session()->has('data')) {
            return response()->json([
                'status' => 1
            ]);
        }
        else {
            return response()->json([
                'status' => 0
            ]);
        }
    }
}