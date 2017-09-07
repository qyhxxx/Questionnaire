<?php

namespace App\Http\Controllers;

use App\Helpers\login;
use Illuminate\Http\Request;

class LogoutController extends Controller {
    public function logout(Request $request) {
        $sso = login::construct();
        $token = $request->session()->get('data')['token'];
        $result = $sso->logout($token);
        if ($result->status) {
            $request->session()->flush();
            return redirect('index')->with([
                'success' => '注销成功'
            ]);
        }
        else {
            return back()->with('fail', '注销失败');
        }
    }
}