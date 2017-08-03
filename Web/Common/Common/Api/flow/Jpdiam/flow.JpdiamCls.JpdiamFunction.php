<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/7/21
 * Time: 9:11
 */
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ErpPublicCode;
trait JpdiamFunction
{
    public static function SetJpdiamToken($value)
    {
        S("jpdiamToken",$value);
    }
    public static function GetJpdiamToken($isForce = false)
    {
        $token = S("jpdiamToken");
        if(empty($token) || $isForce)
        {
            $token = jpdiamCls::GetJpdiamTokenForLogin();
            jpdiamCls::SetJpdiamToken($token);
        }
        return $token;
    }
    public static function GetJpdiamTokenForLogin()
    {
        $json = ErpPublicCode::PostBaseJsonUrlParam(jpdiamCls::$JPDIAMBASEURL
            ,array("action"=>"viplogin","vipid"=>jpdiamCls::$VIPID,"vippsd"=>jpdiamCls::$VIPPSD),true,true);
        $obj = json_decode($json);
        $token = $obj->msgdata->token;
        if(!empty($token))
        {
            JpdiamFunction::SetJpdiamToken($token);
        }
        return $obj->msgdata->token;
    }
    public static function JpdiamPostForJson($action,$paramArr)
    {
        //return JpdiamCls::$testJsonData;
        //S("JpdiamPostForJson1", JpdiamCls::$testJsonData, 500);
        $json = S("JpdiamPostForJson1");
        if(!$json) {
            if (empty($action)) {
                return '';
            }
            $token = jpdiamCls::GetJpdiamToken(true);
            if (empty($token)) {
                return '';
            }
            $arr = array_merge(array("action" => $action, "token" => $token), $paramArr);
            $json = ErpPublicCode::PostBaseJsonUrlParam(jpdiamCls::$JPDIAMBASEURL
                , $arr, true, true);
            $obj = json_encode($json);
            if(!empty($obj->msgdata)) {
                S("JpdiamPostForJson1", $json, 500);
            }
        }
        return $json;
    }
    public static function JpdiamPostForObj($action,$paramArr)
    {
        $json = JpdiamFunction::JpdiamPostForJson($action,$paramArr);
        if(empty($json))
        {
            return null;
        }
        return json_decode($json);
    }
    public static function JpdiamPostForObjData($action,$paramArr)
    {
        $obj = JpdiamFunction::JpdiamPostForObj($action,$paramArr);
        if($obj == null && $obj->status != 1)
        {
            return null;
        }
        return $obj->msgdata;
    }
}