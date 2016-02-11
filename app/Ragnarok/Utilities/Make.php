<?php namespace Alfredoem\Ragnarok\Utilities;

class Make
{
    public static function arrayToObject($array)
    {
        $obj = new \stdClass;
        foreach($array as $k => $v) {
            if(strlen($k)) {
                if(is_array($v)) {
                    $obj->{$k} = self::arrayToObject($v); //RECURSION
                } else {
                    $obj->{$k} = $v;
                }
            }
        }
        return $obj;
    }

    public static function uniqueString()// Return a unique string
    {
        return $customId = strtoupper(uniqid()) . rand(0, 20);
    }
}