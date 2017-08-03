<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/24
 * Time: 10:19
 */
namespace Common\Common\Api\flow;
trait UserPurviewCode
{
    private static function getMemberPurviewInfo($tokenKey,&$myId,&$orderErpId,&$salesErpId,&$userName,&$phone)
    {
        $member = M('member')->field('id,userName,tokenKey,orderErpId,salesErpId,phone')->where(array("tokenKey" => $tokenKey))->select();
        $myId = $member[0]['id'];
        $orderErpId = $member[0]['orderErpId'];
        $salesErpId = $member[0]['salesErpId'];
        $userName = $member[0]['userName'];
        $phone = $member[0]['phone'];
    }
}