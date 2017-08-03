<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/18
 * Time: 14:29
 */
namespace Common\Common\Api\flow;
//验证用户权限
trait UserPurviewOrderErpUser
{
    //判断是否是验证和版房下单用户
    //public static function IsOrderGroupUserLogin($tokenKey)
    public static function IsOrderErpUserLogin($tokenKey)
    {
        $tokenKey = trim($tokenKey);
        $rekey = "ordererpuser";
        if (empty($tokenKey)  || UserCls::IsUserLoginReject($tokenKey,$rekey)) {
            return false;
        }
        $myTokenKey = UserCls::GetTokenKey($tokenKey);
        /*$myId = session('user.id');
        $myErpId = session('user.erpId');
        $orderGroup = session('user.orderGroup');*/
        $myId = UserCls::GetUserId($tokenKey);
        $myOrderErpId = UserCls::GetOrderErpId($tokenKey);
        //$orderGroup = UserCls::GetUserOrderGroup($tokenKey);
        if (empty($myTokenKey) || empty($myId) || empty($myOrderErpId)) {
            UserCls::getMemberPurviewInfo($tokenKey,$myId,$orderErpId,$salesErpId,$userName,$phone);
            if (!empty($userName) && (!empty($orderErpId) || $orderErpId == 0)) {
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

    //离线判断是否是验证和版房下单用户
    //public static function IsOrderGroupUserLoginOffLine($tokenKey)
    public static function IsOrderErpUserLoginOffLine($tokenKey)
    {
        if (UserCls::IsAnyUserLoginOffLine($tokenKey)) {
            $myOrderErpId = UserCls::GetOrderErpId($tokenKey);
            if (!empty($myOrderErpId) || $myOrderErpId == UserPurview::$ORDER_GROUP_FOR_OFFICE_USER_PURVIEW) {
                return true;
            }
        }
        return false;
    }

    public static function IsOrderErpUserForOfficeLogin($tokenKey)
    {
        if(UserCls::IsOrderErpUserLogin($tokenKey))
        {
            $myOrderErpId = UserCls::GetOrderErpId($tokenKey);
            if(is_numeric($myOrderErpId) && $myOrderErpId == UserCls::$ORDER_GROUP_FOR_OFFICE_USER_PURVIEW)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}