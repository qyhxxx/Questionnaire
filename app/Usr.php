<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usr extends Model {
    protected $table = 'usrs';

    protected $primaryKey = 'twt_name';

    protected $fillable = ['twt_name', 'user_number'];

    public $timestamps = true;

    public static function add($data) {
        $usr = self::firstOrCreate($data);
        return $usr;
    }
}