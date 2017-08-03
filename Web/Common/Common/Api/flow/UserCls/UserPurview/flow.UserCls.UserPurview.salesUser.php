<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/18
 * Time: 14:28
 */
namespace Common\Common\Api\flow;
//验证用户权限
trait UserPurviewSalesUser
{
    //判断是否是验证用户
    //public static function IsVerifyUserLogin($tokenKey)
    public static function IsSalesUserLogin($tokenKey)
    {
        $tokenKey = trim($tokenKey);
        $rekey = "salesuser";
        if (empty($tokenKey)  || UserCls::IsUserLoginReject($tokenKey,$rekey)) {
            return false;
        }
        $myTokenKey = UserCls::GetTokenKey($tokenKey);
        //$myId = session('user.id');
        //$myErpId = session('user.erpId');
        $myId = UserCls::GetUserId($tokenKey);
        $mySaleId = UserCls::GetSaleUserId($tokenKey);
        if (empty($myTokenKey) || empty($myId) || empty($mySaleId)) {
            $member = M('member')->field('id,userName,tokenKey,orderErpId,salesErpId,phone')->where(array("tokenKey" => $tokenKey))->select();
            $myId = $member[0]['id'];
            $orderErpId = $member[0]['orderErpId'];
            $salesErpId = $member[0]['salesErpId'];
            $userName = $member[0]['userName'];
            $phone = $member[0]['phone'];
            if (!empty($userName) && !empty($salesErpId)) {
                UserCls::CreateUserLoginSession($tokenKey, $orderErpId, $salesErpId, $myId, $phone);
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

    //离线判断判断是否是验证用户
    public static function IsSalesUserLoginOffLine($tokenKey)
    {
        if (UserCls::IsAnyUserLoginOffLine($tokenKey)) {
            $mySaleId = UserCls::GetSaleUserId($tokenKey);
            if (!empty($myErpId) && $myErpId>0) {
                return true;
            }
        }
        return false;
    }
}