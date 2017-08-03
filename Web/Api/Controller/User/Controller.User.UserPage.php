<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 9:59
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myValidate;
require_once 'UserPage/Controller.User.UserPage.AddressPage.php';
trait UserPage
{
    use AddressPage;
    public function userAdminPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $userTypeName = "未审核用户";
        $path = C('BaseUrl').'images/imageForApi/functions/';
        $functions = array();
        if(UserCls::IsOrderErpUserLogin($myKey))
        {
            $userTypeName = "版房用户";
            $functionsOrder = array(array("keyword"=>"order","pic"=>$path.'order1.png','title'=>'定制下单'));
            $functions = array_merge($functions,$functionsOrder);

        }
        if(UserCls::IsSalesUserLogin($myKey))
        {
            $userTypeName = "成品用户";
            $functionsSales = array(array("keyword"=>"product","pic"=>$path.'product.png','title'=>'成品订单'));
            $functions = array_merge($functions,$functionsSales);
        }
        //$functionsInfo = array(array("pic"=>$path.'information.png','title'=>'信息中心'));
        //$functions = array_merge($functions,$functionsInfo);
        $functionsInfo = array(array("keyword"=>"diamond","pic"=>$path.'diamond.png','title'=>'裸钻库'));
        $functions = array_merge($functions,$functionsInfo);
        $userInfo = UserCls::GetBaseUserInfo($myKey);
        $userInfo["mesCount"] = UserCls::GetUserMessageListNotReadCount($myKey);
        $userInfo['userTypeName'] = $userTypeName;

        //$str = myResponse::ResponseDataTrueDataString(array('userInfo'=>$userInfo,'functionsList'=>$functions));
        echo myResponse::ResponseDataTrueDataString(array('userInfo'=>$userInfo,'functionsList'=>$functions));
    }

    public function userMessagePageList()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/

        $messageList = UserCls::GetUserMessageList(I('get.cpage', ''),$myKey);
        $list_count = UserCls::GetUserMessageListCount($myKey);
        echo myResponse::ResponseDataTrueDataString(array('messageList' => $messageList, 'list_count' => $list_count));
    }
    public function userModifyPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        echo myResponse::ToResponseJsonString(UserCls::userModifyPage($myKey,$memberId));
    }
}