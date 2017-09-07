<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submit extends Model {
    protected $table = 'submits';

    protected $primaryKey = 'sid';

    protected $fillable = ['qnid', 'user_number', 'ip'];

    public static function add($data) {
        $submit = self::add($data);
        return $submit;
    }
}