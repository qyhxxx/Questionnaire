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

    public static function getSubmitsBySidArr($sidArr) {
        $submits = self::whereIn('sid', $sidArr)
            ->distinct()
            ->get();
        return $submits;
    }

    public static function verifyRepeat($twt_name = null, $ip) {
        if ($twt_name != null) {
            $submit = self::where('twt_name', $twt_name)->get();
        }
        else {
            $submit = self::where('ip', $ip)->get();
        }
        if (empty($submit)) {
            return 1;
        }
        else {
            return 0;
        }
    }
}