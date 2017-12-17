<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifiedPhoneController extends Controller
{
    public function getgetVerifiedPhoneQuery(Request $request) {
        $data = $request->session()->get('data');
        $twt_name = $data['twt_name'] ?? 0;
        $token = $data['token'];
        $sso = LoginController::construct();
        $query = $sso->getVerifiedPhoneQuery($twt_name, $token);
        return response()->json([
            'query' => $query
        ]);
    }

    public function getVerifiedPhoneSign(Request $request) {
        $data = $request->all();
        $phone = $data['phone'];
        $token = $data['token'];
        $time = $data['time'];
        $sso = LoginController::construct();
        $sign = $sso->getVerifiedPhoneSign($phone, $token, $time);
        if ($data['sign'] == $sign) {
            $sign = 1;
            $data = $request->session()->get('data');
            $data['phone'] = $phone;
            session(['data' => $data]);
        } else {
            $sign = 0;
        }
        return response()->json([
            'sign' => $sign
        ]);
    }
}
