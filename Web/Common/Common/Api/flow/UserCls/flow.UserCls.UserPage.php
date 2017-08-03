<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/1/3
 * Time: 9:09
 */
namespace Common\Common\Api\flow;

use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ValidateCode;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\isHaveCustomer;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\BllPublic;
trait UserPage
{
    public static function userModifyPage($tokenKey,$memberId)
    {
        $address = UserAddress::getDefultAddress($tokenKey);
        $userInfo = UserInfo::GetBaseUserInfo($tokenKey,true);
        $isShowPrice = UserCls::isShowPrice($tokenKey);
        $isShowOriginalPrice = UserCls::getUserIsShowOriginalPrice($memberId);
        $userModelAddtion = UserCls::getUserModelAddtionPercentValue($memberId,true);
        $userStoneAddtion = UserCls::getUserStoneAddtionPercentValue($memberId,true);
        $data = array("userName"=>$userInfo["userName"]
        ,"headPic"=>$userInfo["headPic"]
        ,"phone"=>$userInfo["phone"]
        ,"isShowPrice"=>$isShowPrice?1:0
        ,"address"=>$address->data["addr"]
        ,"modelAddtion"=>$userModelAddtion
        ,"stoneAddtion"=>$userStoneAddtion
        ,"isMasterAccount"=>intval($userInfo["IsMasterAccount"])
        ,"isShowOriginalPrice"=>$isShowOriginalPrice);
        return myResponse::ResponseDataTrueDataObj($data);
    }
}