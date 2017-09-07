<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usr extends Model {
    protected $table = 'usrs';

    protected $primaryKey = 'user_number';

    protected $fillable = ['user_number', 'twt_name'];

    public $timestamps = true;

    public static function add($data) {
        $usr = self::firstOrCreate($data);
        return $usr;
    }
}