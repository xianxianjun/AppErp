<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/23
 * Time: 15:29
 */
namespace Common\Common\PublicCode;
class WithSql
{
    public static function ForInInteger($field,$split,$str)
    {
        $arr = explode($split,$str);
        $str = '';
        foreach($arr as $value)
        {
            if(is_numeric($value) && is_int($value+0))
            {
                $str = empty($str)?$value:$str.','.$value;
            }
        }
        if(!empty($str))
        {
            $str = ' '.$field.' in ('.$str.')';
        }
        return $str;
    }
    public static function ForInString($field,$split,$str)
    {
        $arr = explode($split,$str);
        $str = '';
        foreach($arr as $value)
        {
            $value = trim($value);
            if(!empty($value))
            {
                $value = str_replace("'","\'",$value);
                $str = empty($str)?"'".$value."'":$str.",'".$value."'";
            }
        }
        if(!empty($str))
        {
            $str = ' '.$field.' in ('.$str.')';
        }
        return $str;
    }

    public static function ForInIntegerOr($fieldArr,$split,$str)
    {
        $or = '';
        $reStr = '';
        $n = 0;
        foreach($fieldArr as $field) {
            $InCon = WithSql::ForInInteger($field, $split, $str);
            if(!empty($InCon))
            {
                $reStr = $reStr.$or.$InCon;
                $or = 'or';
                $n++;
            }
        }
        if(!empty($reStr) && $n > 1)
        {
            $reStr = '('.$reStr.')';
        }
        return $reStr;
    }
    public static function ForInStringOr($fieldArr,$split,$str)
    {
        $or = '';
        $reStr = '';
        $n = 0;
        foreach($fieldArr as $field) {
            $InCon = WithSql::ForInString($field, $split, $str);
            if(!empty($InCon))
            {
                $reStr = $reStr.$or.$InCon;
                $or = 'or';
                $n++;
            }
        }
        if(!empty($reStr) && $n > 1)
        {
            $reStr = '('.$reStr.')';
        }
        return $reStr;
    }
    public static function ForInIntegerAnd($fieldArr,$split,$str)
    {
        $and = '';
        $reStr = '';
        $n = 0;
        foreach($fieldArr as $field) {
            $InCon = WithSql::ForInInteger($field, $split, $str);
            if(!empty($InCon))
            {
                $reStr = $reStr.$and.$InCon;
                $and = 'and';
                $n++;
            }
        }
        if(!empty($reStr) && $n > 1)
        {
            $reStr = '('.$reStr.')';
        }
        return $reStr;
    }
    public static function ForInStringAnd($fieldArr,$split,$str)
    {
        $and = '';
        $reStr = '';
        $n = 0;
        foreach($fieldArr as $field) {
            $InCon = WithSql::ForInString($field, $split, $str);
            if(!empty($InCon))
            {
                $reStr = $reStr.$and.$InCon;
                $and = 'and';
                $n++;
            }
        }
        if(!empty($reStr) && $n > 1)
        {
            $reStr = '('.$reStr.')';
        }
        return $reStr;
    }

    public static function ForLikeOrString($field,$split,$str)
    {
        $arr = explode($split,$str);
        $str = '';
        foreach($arr as $value)
        {
            $value = trim($value);
            if(!empty($value))
            {
                $value = str_replace("'","\'",$value);
                $str = empty($str)?" ".$field." like '%".$value."%'":$str." or ".$field." like '%".$value."%' ";
            }
        }
        if(!empty($str))
        {
            $str = '('.$str.')';
        }
        return $str;
    }
    public static function ForLikeOrOrStrings($fieldArr,$split,$str)
    {
        $or = '';
        $reStr = '';
        $n = 0;
        foreach($fieldArr as $field) {
            $likeCon = WithSql::ForLikeOrString($field, $split, $str);
            if(!empty($likeCon))
            {
                $reStr = $reStr.$or.$likeCon;
                $or = 'or';
                $n++;
            }
        }
        if(!empty($reStr) && $n > 1)
        {
            $reStr = '('.$reStr.')';
        }
        return $reStr;
    }
    public static function ForLikeAndString($field,$split,$str)
    {
        $arr = explode($split,$str);
        $str = '';
        foreach($arr as $value)
        {
            $value = trim($value);
            if(!empty($value))
            {
                $value = str_replace("'","\'",$value);
                $str = empty($str)?" ".$field." like '%".$value."%'":$str." and ".$field." like '%".$value."%' ";
            }
        }
        if(!empty($str))
        {
            $str = '('.$str.')';
        }
        return $str;
    }
    public static function ForLikeAndOrStrings($fieldArr,$split,$str)
    {
        $or = '';
        $reStr = '';
        $n = 0;
        foreach($fieldArr as $field) {
            $likeCon = WithSql::ForLikeAndString($field, $split, $str);
            if(!empty($likeCon))
            {
                $reStr = $reStr.$or.$likeCon;
                $or = 'or';
            }
        }
        if(!empty($reStr) && $n > 1)
        {
            $reStr = '('.$reStr.')';
        }
        return $reStr;
    }

    public static function ForGtLtEqString($value,$field,$multiplicationParam=1)
    {
        $str = '';
        if(!empty($value))
        {
            $valueArr = explode('|',$value);
            if(count($valueArr)==1 && is_numeric($valueArr[0]))
            {
                $str = ' '.$field.'='.$valueArr[0]*$multiplicationParam;
            }
            else if(count($valueArr)==2)
            {
                if(is_numeric($valueArr[0]) && is_numeric($valueArr[1])) {
                    $str = ' ('.$field.'>=' . $valueArr[0]*$multiplicationParam . ' and '.$field.'<=' . $valueArr[1]*$multiplicationParam.")";
                }
                else if(is_numeric($valueArr[0]))
                {
                    $str = ' '.$field.'>=' . $valueArr[0]*$multiplicationParam;
                }
                else if(is_numeric($valueArr[1]))
                {
                    $str = ' '.$field.'<=' . $valueArr[1]*$multiplicationParam;
                }
            }

        }
        return $str;
    }
}