<?php
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
trait UserSetFunction
{
    public static function isShowPrice($tokenKey)
    {
        /*$SetFunction = UserCls::GetUserSetFunction($tokenKey);
        if($SetFunction)
        {
            $obj = json_decode($SetFunction);
            if($obj->isShowPrice == 1)
            {
                return true;
            }
        }
        return false;*/
        return true;
    }
    public static function UpdateisShowPrice($tokenKey,$value)
    {
        return UserCls::UpdateUserSetFunction($tokenKey,"isShowPrice",$value);
    }
}
trait UserSetFunctionBase
{
    public static function GetUserSetFunction($tokenKey)
    {
        $SetFunction = S("UserSetFunction".$tokenKey);
        if(!$SetFunction)
        {
            $SetFunction = M("member")->where("tokenKey='".$tokenKey."'")->getField("SetFunction");
            UserCls::SetUserSetFunction($tokenKey,$SetFunction);
        }
        return $SetFunction;
    }
    public static function SetUserSetFunction($tokenKey,$value)
    {
        S("UserSetFunction".$tokenKey,$value);
    }
    public static function SetUserSetFunctionEmpty($tokenKey)
    {
        UserCls::SetUserSetFunction($tokenKey,null);
    }
    public static function UpdateUserSetFunction($tokenKey,$key,$value)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if($member_id>0) {
            $SetFunction = M("member")->where("id=" . $member_id)->getField("SetFunction");
            $obj = json_decode($SetFunction);
            $obj->$key = $value;
            $data["SetFunction"] = json_encode($obj);
            M("member")->where("id=" . $member_id)->save($data);
            UserCls::SetUserSetFunctionEmpty($tokenKey);
            UserCls::GetUserSetFunction($tokenKey);
            return true;
            /*if($re)
            {
                UserCls::SetUserSetFunctionEmpty($tokenKey);
                return true;
            }*/
        }
        return false;
    }
}