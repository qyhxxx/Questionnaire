<?php

namespace App\Http\Controllers;

use App\Helpers\login;
use Illuminate\Http\Request;

class LoginController extends Controller {
    public static function login(Request $request) {
//        $link = $request->path();

        $link = "http://survey.twtstudio.com/";
        login::login($link);
    }

    public function loginStatus(Request $request) {
        if ($request->session()->has('data')) {
            return 1;
        }
        else {
            return 0;
        }
    }
}