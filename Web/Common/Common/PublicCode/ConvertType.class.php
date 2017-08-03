<?php
namespace Common\Common\PublicCode;
class ConvertType
{
    public static function ConvertInt($value,$default)
    {
        if(is_numeric($value) && is_int($value+0))
        {
            return intval($value);
        }
        else
        {
            return $default;
        }
    }
}