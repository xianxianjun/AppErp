<?php
namespace Common\Common\Api\Code;
use Common\Common\PublicCode\FunctionCode;
class myValidate {
    public static function ConnectStr($arr)
    {
        $str = '';
        $isHave = false;
        foreach($arr as $value)
        {
            $str = $isHave?$str.'^'.str_replace('^','',$value):str_replace('^','',$value);
            $isHave = true;
        }
        return $str;
    }
    public static function IsArrIntegerForSplit($str,$split)
    {
        if(empty($str) || empty($split)){return false;}
        return self::IsArrInteger(explode($split,$str));
    }
    public static function IsArrInteger($arr)
    {
        if($arr == null || count($arr)<=0){return false;}
        foreach($arr as $value) {
            $str = trim($value);
            if(!FunctionCode::isInteger($str))
            {
                return false;
            }
        }
        return true;
    }
    public static function IsArrStringNotEmtry($arr)
    {
        if($arr == null || count($arr)<=0){return false;}
        foreach($arr as $value) {
            $str = trim($value);
            if(empty($str))
            {
                return false;
            }
        }
        return true;
    }
    public static function IsArrStringHaveNotEmtry($arr)
    {
        foreach($arr as $value) {
            $str = trim($value);
            if(!empty($str))
            {
                return true;
            }
        }
        return false;
    }
    public static function ValidateEmtry($arr)
    {
        foreach($arr as $value)
        {
            $valueArr = explode('^',$value);
            $str = empty($valueArr[0])?'':trim($valueArr[0]);
            $len = intval($valueArr[1])?$valueArr[1]:0;
            $ErrMes = $valueArr[2];

            if(empty($str) || strlen($str)<=$len)
            {
                return $ErrMes;
                break;
            }
        }
        return '';
    }
    public static function VlidateIntegerGt($arr)
    {
        foreach($arr as $value)
        {
            $valueArr = explode('^',$value);
            $str = strlen($valueArr[0])<=0?'':trim($valueArr[0]);
            $min = intval($valueArr[1])?$valueArr[1]:0;
            $ErrMes = $valueArr[2];

            if(strlen($str)<=0 || !FunctionCode::isInteger($str) || intval($str)<$min)
            {
                return $ErrMes;
                break;
            }
        }
        return '';
    }
    public static function VlidateIntegerLt($arr)
    {
        foreach($arr as $value)
        {
            $valueArr = explode('^',$value);
            $str = empty($valueArr[0])?'':trim($valueArr[0]);
            $max = intval($valueArr[1])?$valueArr[1]:0;
            $ErrMes = $valueArr[2];

            if(empty($str) || is_int($str) || intval($str)>$max)
            {
                return $ErrMes;
                break;
            }
        }
        return '';
    }
}