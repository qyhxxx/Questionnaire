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
        $from = $_GET['from'] ?? urlencode("https://survey.twtstudio.com/index");
        $link = "https://survey.twtstudio.com/api/?from=" . $from;
        header("Location:".$sso->getLoginUrl($link));
        exit;
    }

    public static function storage($token) {
        $sso = self::construct();
        $userinfo = $sso->fetchUserInfo($token);
        if ($userinfo->status == 1) {
            $result = $userinfo->result;
            $data['id'] = $result->id;
            $data['user_number'] = $result->user_number;
            $data['twt_name'] = $result->twt_name;
            $data['type'] = 0;
            $data['real_name'] = $result->user_info->username;
            Usr::add($data);
            $data['token'] = $token;
            session(['data' => $data]);
            session()->save();
            return 1;
        }
        else {
            return 0;
        }
    }

    public function loginStatus(Request $request) {
        if ($request->session()->has('data')) {
            $twt_name = $request->session()->get('data')['twt_name'];
            return response()->json([
                'status' => 1,
                'twt_name' => $twt_name
            ]);
        }
        else {
            return response()->json([
                'status' => 0
            ]);
        }
    }
}