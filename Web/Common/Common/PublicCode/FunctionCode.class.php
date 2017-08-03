<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/28
 * Time: 10:22
 */
namespace Common\Common\PublicCode;
class FunctionCode
{
    public static function GetNowTimeDate()
    {
        return date('Y-m-d H:i:s', time());
    }
    public static function GetNowDate()
    {
        return date('Y-m-d', time());
    }

    public static function isInteger($value)
    {
        return is_numeric($value) && is_int($value + 0);
    }

    public static function FindEqArr($arr, $fineField, $eqValue)
    {
        if (empty($eqValue) && $eqValue!='0') return null;
        foreach ($arr as $value) {
            if ($value[$fineField] == $eqValue) {
                return $value;
            }
        }
        return null;
    }

    public static function isDate($time)
    {
        return preg_match("/^[0-9]{4}(\-|\/)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$time);
    }
    public static function FindEqArrReN(&$arr,$fineField,$eqValue)
    {
        if(empty($eqValue) && $eqValue!='0') return null;
        $n = 0;
        foreach($arr as &$value)
        {
            if($value[$fineField] == $eqValue)
            {
                return $n;
            }
            $n++;
        }
        return -1;
    }
    public static function FindEqObjReN(&$arr,$fineField,$eqValue)
    {
        if(empty($eqValue) && $eqValue!='0') return null;
        $n = 0;
        foreach($arr as &$value)
        {
            if($value->$fineField == $eqValue)
            {
                return $n;
            }
            $n++;
        }
        return -1;
    }
    //对象-》对象
    public static function FindEqObjReField(&$arr,$findField,$reField,$eqValue)
    {
        if(empty($eqValue) && $eqValue!='0') return null;
        $n = 0;
        foreach($arr as &$value)
        {
            if($value->$findField == $eqValue)
            {
                return $value->$reField;
            }
            $n++;
        }
        return "";
    }
    //对象-》对象
    public static function FindEqObjReObjItem(&$arr,$findField,$reField,$eqValue)
    {
        if(empty($eqValue) && $eqValue!='0') return null;
        $n = 0;
        foreach($arr as &$value)
        {
            if($value->$findField == $eqValue)
            {
                return $value;
            }
            $n++;
        }
        return "";
    }
    //数组-》对象
    public static function FindEqObjReItem($arr,$fineField,$eqValue)
    {
        $value = FunctionCode::FindEqObjReN($arr,$fineField,$eqValue);
        if($value!=-1)
        {
            return $arr[$value];
        }
        else
        {
            return "";
        }
    }
    //数组-》数组
    public static function FindEqArrReField($arr,$fineField,$reField,$eqValue)
    {
        $value = FunctionCode::FindEqArr($arr,$fineField,$eqValue);
        if($value!=null)
        {
            return $value[$reField];
        }
        else
        {
            return "";
        }
    }

    public static function ConnectStrArrForComm($baseStr,$itemArr,$Comm)
    {
        foreach($itemArr as $value)
        {
            $baseStr = FunctionCode::ConnectStrForComm($baseStr,$value,$Comm);
        }
        return $baseStr;
    }
    public static function ConnectStrForComm($baseStr,$item,$Comm)
    {
        if(empty($item))
        {
            return $baseStr;
        }
        if(empty($baseStr))
        {
            return $item;
        }
        if(substr_count($Comm.$baseStr.$Comm,$Comm.$item.$Comm) > 0)
        {
            return $baseStr;
        }
        return $baseStr.$Comm.$item;
    }
    public static function ReurnDefault($value,$isValue,$reValue)
    {
        if(trim($isValue) != trim($value))
        {
            return $value;
        }
        else
        {
            return $reValue;
        }
    }
    public static function ReurnArrDefault($value,$IsArrValue,$reValue)
    {
        foreach($IsArrValue as $isValue)
        {
            if(trim($isValue) == trim($value))
            {
                return $reValue;
            }
        }
        return $value;
    }
    public static function getTimeId()
    {
        return date('YmdHis', time()).str_pad(intval(microtime()*1000),4,0);
    }
    public static function ArrToFieldArr($arr,$field)
    {
        $reArr = array();
        foreach($arr as $value)
        {
            $reArr[] = $value[$field];
        }
        return $reArr;
    }
    public static function ArrObjToFieldArr($arrObj,$field)
    {
        $fieldarr = array($field);
        return FunctionCode::ArrObjToFieldsArr($arrObj,$fieldarr);
    }
    public static function ArrObjToFieldsArr($arrObj,$fieldarr,$mergeArr = array())
    {
        $arr = array();
        foreach($arrObj as $value)
        {
            $item = array();
            foreach($fieldarr as $field)
            {
                if(is_array($field) && count($field)>1)
                {
                    $item[$field[1]] = $value->$field[0];
                }
                else {
                    $item[$field] = $value->$field;
                }
            }
            if(count($mergeArr)>0)
            {
                $item = array_merge($item,$mergeArr);
            }
            $arr[] = $item;
        }
        return $arr;
    }
    public static function ArrayConnByChar($arr,$connChar)
    {
        if($arr==null || count($arr)<=0)
        {
            return "";
        }
        $str = "";
        $connChartmp = "";
        foreach($arr as $val)
        {
            if(!empty($val)) {
                $str = $str . $connChartmp . $val;
                $connChartmp = $connChar;
            }
        }
        return $str;
    }
    public static function array2object($array) {
        if (is_array($array)) {
            $obj = new StdClass();
            foreach ($array as $key => $val){
                $obj->$key = $val;
            }
        }
        else { $obj = $array; }
        return $obj;
    }
    public static function object2array($object) {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        }
        else {
            $array = $object;
        }
        return $array;
    }
    //中文截取实际长度
    public static function curStr($str, $length,$after = "")
    {
        if(strlen($str)<=$length)
        {
            return $str;
        }
        $len = mb_strlen($str, "UTF-8");
        $tleng = strlen($after);
        $restr = "";
        for ($i = 0; $i < $len; $i += 1) {
            $strItem = mb_substr($str, $i, 1, "UTF-8");
            $tleng = $tleng + strlen($strItem);
            if($tleng>=$length)
            {
                return $restr.$after;
            }
            else
            {
                $restr = $restr . $strItem;
            }
        }
    }
    public static function CopyEmptyObj($source)
    {
        $valueObj = clone $source;
        foreach($source as $key=>$value)
        {
            if(is_array($valueObj)) {
                $valueObj[$key] = "";
            }
            else
            {
                $valueObj->$key = "";
            }
        }
        return $valueObj;
    }
    public static function GetWebParam($addStr)
    {
        if(C('isTest') == 1) {
            if (is_array($_GET)) {
                foreach ($_GET as $key => $value) {
                    if (is_array($_GET[$key])) {
                        foreach ($_GET[$key] as $key2 => $value2) {
                            $mywayGet_Var[$key][$key2] = $value2;
                        }
                    } else {
                        $mywayGet_Var[$key] = $value;
                    }
                }
            }
            //读POST变量到数组
            if (is_array($_POST)) {
                foreach ($_POST as $key => $value) {
                    if (is_array($_POST[$key])) {
                        foreach ($_POST[$key] as $key2 => $value2) {
                            $mywayPost_Var[$key][$key2] = $value2;
                        }
                    } else {
                        $mywayPost_Var[$key] = $value;
                    }
                }
            }
            $get = "";
            foreach ($mywayGet_Var as $key => $value) {
                $get = $get . ";" . $key . "=" . $value;
            }
            $post = "";
            foreach ($mywayPost_Var as $key => $value) {
                $post = $post . ";" . $key . "=" . $value;
            }
            $txt = $addStr . "【" . FunctionCode::GetNowTimeDate() . "】\r\n当前地址:" . 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]
                . "\r\n触发地址:" . $_SERVER['HTTP_REFERER'] . "\r\nGET:" . $get . "\r\n" . "POST:" . $post . "\r\n\r\n\r\n";
            file_put_contents(BASE_PATH . "/log/mypaylog.txt", $txt, FILE_APPEND);
        }
    }
    //获取完整url
    public static function get_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }
    public static function GetRootURL()
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on")
        {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        $pageURL = $pageURL."/";
        return $pageURL;
    }
    //获取无参数url
    public static function curPageURL()
    {
        $pageURL = 'http';

        if ($_SERVER["HTTPS"] == "on")
        {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        $this_page = $_SERVER["REQUEST_URI"];

        // 只取 ? 前面的内容
        if (strpos($this_page, "?") !== false)
        {
            $this_pages = explode("?", $this_page);
            $this_page = reset($this_pages);
        }

        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $this_page;
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . $this_page;
        }
        return $pageURL;
    }
}