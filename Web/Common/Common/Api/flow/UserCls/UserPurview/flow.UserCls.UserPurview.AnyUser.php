<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/18
 * Time: 14:21
 */
namespace Common\Common\Api\flow;
//验证用户权限
trait UserPurviewAnyUser
{
    //只判断是否是验证用户
    public static function IsAnyUserLogin($tokenKey)
    {
        $tokenKey = trim($tokenKey);
        $rekey = "anyuser";
        if (empty($tokenKey) || UserCls::IsUserLoginReject($tokenKey,$rekey)) {
            return false;
        }
        $myTokenKey = UserCls::GetTokenKey($tokenKey);
        //$myId = session('user.id');
        $myId = UserCls::GetUserId($tokenKey);
        if (empty($myTokenKey) || empty($myId)) {
            UserCls::getMemberPurviewInfo($tokenKey,$myId,$orderErpId,$salesErpId,$userName,$phone);
            if (!empty($userName)) {
                $myTokenKey = $tokenKey;
                UserCls::CreateUserLoginSession($myTokenKey, $orderErpId, $salesErpId, $myId, $phone);
                return true;
            }
            else
            {
                UserCls::CreateUserLoginReject($tokenKey,$rekey);
            }
        } else if ($myTokenKey == $tokenKey) {
            return true;
        }
        return false;
    }

    //离线判断是否是验证用户
    public static function IsAnyUserLoginOffLine($tokenKey)
    {
        $tokenKey = trim($tokenKey);
        if (empty($tokenKey)) {
            return false;
        }
        $myTokenKey = UserCls::GetTokenKey($tokenKey);
        if ($myTokenKey == $tokenKey) {
            return true;
        }
        return false;
    }
}