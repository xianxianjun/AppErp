<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 10:56
 */
namespace Common\Common\Api\flow;
trait UserCode
{
    public static function CreateTokenKey($userName, $password, $phoneCode)
    {
        return md5($userName . $password . $phoneCode);//.$erpId.$orderGroup
    }
}