<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller {
    public function logout(Request $request) {
        $sso = LoginController::construct();
        $token = $request->session()->get('data')['token'];
        $result = $sso->logout($token);
        if ($result->status) {
            $request->session()->flush();
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