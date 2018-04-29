<?php

namespace app\Http\Controllers\Admin;

use App\Helpers\functions;
use App\Usr;

class UserController {
    public function listOfUsers() {
        $users = Usr::getAllUsrs();
        return view('User.list', ['users' => $users]);
    }

    public function toSupMng($twt_name) {
        $update = ['type' => 1];
        Usr::updateUsr($twt_name, $update);
        functions::popup('设为超管成功');
        functions::skip(url('user/list'));
    }

    public function toOrdMng($twt_name) {
        $name = session()->get('twt_name');
        if ($name == $twt_name) {
            functions::popup('不能取消当前账号的超管权限');
            functions::skip(url('user/list'));
        } else {
            $update = ['type' => 0];
            Usr::updateUsr($twt_name, $update);
            functions::popup('取消超管成功');
            functions::skip(url('user/list'));
        }
    }
}