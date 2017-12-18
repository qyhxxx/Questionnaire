<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usr extends Model {
    protected $table = 'usrs';

    protected $primaryKey = 'twt_name';

    protected $fillable = ['twt_name', 'user_number', 'type', 'phone'];

    public $timestamps = true;

    public static function add($data) {
        $usr = self::firstOrCreate($data);
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
}