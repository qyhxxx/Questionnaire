<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usr extends Model {
    protected $table = 'usrs';

    protected $primaryKey = 'id';

    protected $fillable = ['id', 'twt_name', 'user_number', 'type', 'phone'];

    public $timestamps = true;

    public static function add($data) {
        $usr = self::find($data['id']);
        if ($usr != null) {
            $usr->update($data);
            $usr = self::find($data['id']);
        } else {
            $usr = self::create($data);
        }
        return $usr;
    }

    public static function getUsr($twt_name) {
        $usr = self::where('twt_name', $twt_name)->first();
        return $usr;
    }

    public static function updateUsr($twt_name, $data) {
        self::where('twt_name', $twt_name)->update($data);
        $usr = self::getUsr($twt_name);
        return $usr;
    }

    public static function getNumberByName($twt_name){
        $data = self::where('twt_name', $twt_name)->first();
        $user_number = $data['user_number'];
        return $user_number;
    }

    public static function getTypeByName($twt_name){
        $data = self::where('twt_name', $twt_name)->first();
        $type = $data['type'];
        return $type;
    }
}