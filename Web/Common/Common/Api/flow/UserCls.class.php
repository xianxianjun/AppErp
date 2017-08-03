<?php

/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/18
 * Time: 16:13
 */
//用户
namespace Common\Common\Api\flow;
require_once 'UserCls/FLOW.USERCLS.INC.php';
class UserCls
{
    use UserSessionManage, UserInfo, UserDo,UserConfig,UserAddress,UserCode,UserPurviewCode,UserPage;
    use UserPurviewAnyUser,UserPurviewSalesUser,UserPurviewOrderErpUser,UserPurviewConfig;
    use UserSetFunctionBase,UserSetFunction,UserParameter;
    //自提地址
    public static $PickDefaultAddress = array("id"=>0,"name"=>"自提","addr"=>"广东省 深圳市罗湖区 水贝二路 19栋","phone"=>"011236569","isDefault"=>0);
}
?>
