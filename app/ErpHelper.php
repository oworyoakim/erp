<?php


namespace App;


use stdClass;

class ErpHelper
{
    public static function arrayToObject(array $arr) {
        if(count($arr) == 0){
            return null;
        }
        return json_decode(json_encode($arr), FALSE);
    }
    public static function arrayToObjectRecursive(array $arr) {
        $obj = new stdClass();
        foreach($arr as $key => $val) {
            if (is_array($val)) {
                $val = self::arrayToObject($val);
            }
            $obj->$key = $val;
        }
        return $obj;
    }
}
