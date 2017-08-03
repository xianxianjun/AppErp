<?php

namespace Common\Common\PublicCode;
use Common\Common\Api\flow\UserCls;

class ErpPublicCode {
    //public static $ErpBaseReceiveUrl = "http://211.162.71.165:8087/Receive";
    //public static $ErpBaseRespondUrl = "http://211.162.71.165:8087/Respond";
    //public static $ErpBaseReceiveUrl = C('ErpBaseReceiveUrl');
    //public static $ErpBaseRespondUrl = C('ErpBaseRespondUrl');
    //public static $webUsername = "oa.mstar.cn";
    //public static $webKeyword = "201606161630";
    public static function  getTempUser($curl)
    {
        if (empty($_SESSION["TempName"])) {
            //dirname(__FILE__)=E:\wamp\www\QxWeb\AppMall\Controller
            $_SESSION["TempName"] = tempnam(dirname(__FILE__) . '\\temp', 'cookie');
            curl_setopt($curl, CURLOPT_COOKIEJAR, $_SESSION["TempName"]);
        }
        //echo $_SESSION["TempName"];
        //http://localhost:9090/index.php?m=QxWeb&a=lists
        curl_setopt($curl, CURLOPT_COOKIEFILE, $_SESSION["TempName"]);
        return $_SESSION["TempName"];
    }
    public static function SetJsonObjString($error, $data, $message)
    {
        $objt = ErpPublicCode::SetJsonObj($error, $data, $message);
        return json_encode($objt);
    }
    public static function GetBindErpMemberId()
    {
        if(empty($_SESSION['erpMemberId']))
        {
            return '';
        }
        else
        {
            return $_SESSION['erpMemberId'];
        }
    }
    public static function SetJsonObj($error, $data, $message)
    {
        $objt = array(
            'response' => '',
            'error' => $error,
            'message' => $message,
            'data' => $data
        );
        return $objt;
    }
    public static function array_merge_obj($MouldObj,$DataObj)
    {
        $ret = $MouldObj;
        foreach ($DataObj as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == "object"){
                $ret->$key = ErpPublicCode::array_merge_obj($ret->$key,$value);
            }else{
                $ret->$key = $value;
            }
        }
        return $ret;
    }
    public static function array_merge_obj_bak($MouldObj,$DataObj)
    {
        $ret = $MouldObj;
        foreach ($DataObj as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == "object"){
                /*if(is_numeric($key) && is_int($key+0)>0) {
                    $ret[$key] = ErpPublicCode::array_merge_obj($ret->$key, $value);
                }
                else {
                    $ret->$key = ErpPublicCode::array_merge_obj($ret->$key, $value);
                }*/
                $ret->$key = ErpPublicCode::array_merge_obj($ret->$key, $value);
            }else{
                $ret->$key = $value;
            }
        }
        return $ret;
    }
    public static function SetBindErpMemberId($id)
    {
        $_SESSION['erpMemberId'] = $id;
    }
    public static function isBindErpMember()
    {
        $mid = ErpPublicCode::GetBindErpMemberId();
        if(empty($mid))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function GetSendJsonObj($event,$title,$ArrData)
    {
        $sendData = array(
            "event"=>$event,
            "title"=>$title,
            "data"=>$ArrData
        );
        return $sendData;
    }
    public static function GetSendJsonString($event,$title,$ArrData)
    {
        return json_encode(ErpPublicCode::GetSendJsonObj($event,$title,$ArrData));
    }
    public static function GetSendJsonStringObj($event,$title,$ArrData)
    {
        header('content-type:application/json;charset=utf8');
        return json_encode(ErpPublicCode::GetSendJsonObj($event,$title,$ArrData));
    }
    public static function PostBaseUrl($url,$datastr,$isUnescape = true,$isSSL = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($isSSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        //curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
//为了支持cookie
        //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        ErpPublicCode::getTempUser($ch);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datastr);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return ErpPublicCode::SetJsonObjString('1', '', $err);
        }
        curl_close($ch);
        $json = iconv("utf-8", "utf-8//IGNORE", $json);
        header('Content-type: text/html; charset=UTF8');
        if($isUnescape) {
            return ErpPublicCode::unescape($json);
        }
        else
        {
            return $json;
        }
    }
    public static function PostBaseJsonUrlParam($url, $sdata,$isUnescape = true,$isSSL=false)
    {
        $datastr = '';
        if(!empty($sdata) && count($sdata)>0)
        {
            $and = '';
            foreach($sdata as $key=>$value)
            {
                if(!empty($key) && (!empty($value) || $value==0)) {
                    $datastr = $datastr.$and . $key . "=" . $value;
                    $and = '&';
                }
            }
        }
        return ErpPublicCode::PostBaseUrl($url,$datastr,$isUnescape,$isSSL);
    }
    public static function PostBaseJsonUrl($url, $data,$isUnescape = true)
    {

        $datastr = "json=".urlencode($data);
        return ErpPublicCode::PostBaseUrl($url,$datastr,$isUnescape);
        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
//为了支持cookie
        //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        ErpPublicCode::getTempUser($ch);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return ErpPublicCode::SetJsonObjString('1', '', $err);
        }
        curl_close($ch);
        $json = iconv("utf-8", "utf-8//IGNORE", $json);
        header('Content-type: text/html; charset=UTF8');
        if($isUnescape) {
            return ErpPublicCode::unescape($json);
        }
        else
        {
            return $json;
        }*/
    }
    public static function GetBaseUrlJson($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        ErpPublicCode::getTempUser($ch);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return ErpPublicCode::SetJsonObjString('1', '', $err);
        }
        curl_close($ch);
        //echo C('BaseUrl') . $url;
        $json = iconv("utf-8", "utf-8//IGNORE", $json);
        $json = ErpPublicCode::unescape($json);
        header('Content-type: text/html; charset=UTF8');
        ob_clean();
        return $json;
    }
    public static function GetUrl($event,$title,$arrValue = array())
    {
        $str = '';
        $Iserpid = false;
        $Isappmid = false;
        if(is_array($arrValue) && count($arrValue)>0)
        {
            $ent = '&';
            foreach($arrValue as $key=>$value)
            {
                if($key == "erpid")
                {
                    $Iserpid = true;
                }
                else if($key == "appmid")
                {
                    $Isappmid = true;
                }
                $str =  $str.$ent.$key."=".$value;
            }
        }
        if (!$Iserpid || !$Isappmid) {//加用户ID
            $myKey = UserCls::GetRequestTokenKey();
            $ent = '&';
            if (!empty($myKey)) {
                if (!$Iserpid) {
                    $erpid = UserCls::GetOrderErpId($myKey);
                    $str = $str . $ent . "erpid=" . $erpid;
                }
            }
            if (!empty($myKey)) {
                if (!$Isappmid) {
                    $memberId = UserCls::GetUserId($myKey);
                    $str = $str . $ent . "appmid=" . $memberId;
                }
            }
        }
        $t = time();
        $v = md5(C('webUsername').$t.C('webKeyword'));
        return ErpPublicCode::GetBaseUrlJson(C('ErpBaseRespondUrl')."?event=$event&title=$title&timestamp=$t&verifycode=$v".$str);
    }
    public static function GetUrlObj($event,$title,$arrValue = array())
    {
        $obj = null;
        $json = ErpPublicCode::GetUrl($event,$title,$arrValue);
        if (strlen($json) > 0) {
            $obj = json_decode($json);
        }
        //echo $obj->error;
        return $obj;
    }
    public static function GetUrlObjData($event,$title,$arrValue = array())
    {
        $obj = ErpPublicCode::GetUrlObj($event,$title,$arrValue);
        //echo json_encode($obj->data->webPage);
        if ($obj != null) {
            return $obj->data;
        }
        return null;
    }
    public static function GetUrlObjCheckErr($event,$title,$arrValue = array())
    {
        $obj = ErpPublicCode::GetUrlObj($event,$title,$arrValue);
        if ($obj == null || intval($obj->error) >= 1) {
            //echo '<script>alert('.json_encode($obj->message).')</script>';
            $isLocation = true;
            if(empty($BackUrl)) {
                $burl = $_SERVER['HTTP_REFERER'];
                if(intval(strpos($burl, $_SERVER['SERVER_NAME'], 7)) <= 0)
                {
                    $isLocation = false;
                }
            }
            else
            {
                $burl = $BackUrl;
            }
            //echo $url.'='.$_SERVER['SERVER_NAME'].'='.intval(strpos($url, $_SERVER['SERVER_NAME'],7));
            if (!empty($burl) && $isLocation) {
                setcookie('ErrReturnUrl', $burl);
            }
            header("Location: indexErp.php?m=QxWeb&a=Err&mes=".urlencode($obj->message));
            exit;
        }
        return $obj;
    }
    public static function GetUrlObjDataCheckErr($event,$title,$arrValue = array())
    {
        $obj = ErpPublicCode::GetUrlObjCheckErr($event,$title,$arrValue);
        //echo json_encode($obj->data->webPage);
        if ($obj != null) {
            return $obj->data;
        }
        return null;
    }
    public static function PostUrl($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue = array(),$isUnescape = true)
    {
        $json = ErpPublicCode::GetUrl($getEvent,$getTitle,$arrValue = array());
        if(!empty($json)) {
            $str = '';
            if(is_array($getArrValue) && count($getArrValue)>0)
            {
                $ent = '&';
                foreach($getArrValue as $key=>$value)
                {
                    $str =  $str.$ent.$key."=".$value;
                }
                if(!empty($str))
                {
                    $str = "?".$str;
                }
            }

            $obj = json_decode($json);
            //$objdata = PublicCode::objarray_to_array($obj->data);
            //ErpPublicCode::array_merge_obj($obj->data,$data);
            $obj->data = $data;
            //$obj->data = $newdata;
            $obj->event = $postEvent;
            $obj->title = $postTitle;
            $t = $obj->sNumber;
            $v = md5(C('webUsername').$t.C('webKeyword'));
            $obj->verifyCode = $v;
            return ErpPublicCode::PostBaseJsonUrl(C('ErpBaseReceiveUrl').$str, json_encode($obj),$isUnescape);
        }
    }
    public static function PostUrlForObj($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue = array())
    {
        $obj = null;
        $json = ErpPublicCode::PostUrl($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue);
        //echo $json;
        if (strlen($json) > 0) {
            $obj = json_decode($json);
        }
        //echo $obj->error;
        return $obj;
    }
    public static function PostUrlForObjData($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue = array())
    {
        $obj = ErpPublicCode::PostUrlForObj($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue);
        //echo json_encode($obj->data->webPage);
        if ($obj != null) {
            return $obj->data;
        }
        return null;
    }
    public static function PostUrlForObjCheckErr($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue = array())
    {
        $obj = ErpPublicCode::PostUrlForObj($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue);
        if ($obj == null || intval($obj->error) >= 1) {
            //echo '<script>alert('.json_encode($obj->message).')</script>';
            $isLocation = true;
            if(empty($BackUrl)) {
                $burl = $_SERVER['HTTP_REFERER'];
                if(intval(strpos($burl, $_SERVER['SERVER_NAME'], 7)) <= 0)
                {
                    $isLocation = false;
                }
            }
            else
            {
                $burl = $BackUrl;
            }
            //echo $url.'='.$_SERVER['SERVER_NAME'].'='.intval(strpos($url, $_SERVER['SERVER_NAME'],7));
            if (!empty($burl) && $isLocation) {
                setcookie('ErrReturnUrl', $burl);
            }
            header("Location: indexErp.php?m=QxWeb&a=Err&mes=".urlencode($obj->message));
            exit;
        }
        return $obj;

    }
    public static function PostUrlForObjDataCheckErr($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue = array())
    {
        $obj = ErpPublicCode::PostUrlForObjCheckErr($data,$postEvent,$postTitle,$getEvent,$getTitle,$getArrValue);
        //echo json_encode($obj->data->webPage);
        if ($obj != null) {
            return $obj->data;
        }
        return null;
    }
    public static function unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f)
                    $ret .= chr($val);
                else
                    if ($val < 0x800)
                        $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
                    else
                        $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
                $i += 5;
            } else
                if ($str[$i] == '%') {
                    $ret .= urldecode(substr($str, $i, 3));
                    $i += 2;
                } else
                    $ret .= $str[$i];
        }
        return $ret;
    }

    public static function escape($string)
    {
        $n = $bn = $tn = 0;
        $output = '';
        $special = "-_.+@/*0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        while ($n < strlen($string)) {
            $ascii = ord($string[$n]);
            if ($ascii == 9 || $ascii == 10 || (32 <= $ascii && $ascii <= 126)) {
                $tn = 1;
                $n++;
            } elseif (194 <= $ascii && $ascii <= 223) {
                $tn = 2;
                $n += 2;
            } elseif (224 <= $ascii && $ascii <= 239) {
                $tn = 3;
                $n += 3;
            } elseif (240 <= $ascii && $ascii <= 247) {
                $tn = 4;
                $n += 4;
            } elseif (248 <= $ascii && $ascii <= 251) {
                $tn = 5;
                $n += 5;
            } elseif ($ascii == 252 || $ascii == 253) {
                $tn = 6;
                $n += 6;
            } else {
                $n++;
            }
            $singleStr = substr($string, $bn, $tn);
            $charVal = bin2hex(iconv('utf-8', 'ucs-2', $singleStr));
            if (base_convert($charVal, 16, 10) > 0xff) {
                if (!preg_match("/win/i", PHP_OS))
                    $charVal = substr($charVal, 2, 2) . substr($charVal, 0, 2);
                $output .= '%u' . $charVal;
            } else {
                if (false !== strpos($special, $singleStr))
                    $output .= $singleStr;
                else
                    $output .= "%" . dechex(ord($string[$bn]));
            }

            $bn = $n;
        }
        return $output;
    }
    public static function objarray_to_array($obj) {
        $ret = array();
        foreach ($obj as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == "object"){
                $ret[$key] =  PublicCode::objarray_to_array($value);
            }else{
                $ret[$key] = $value;
            }
        }
        return $ret;
    }
}