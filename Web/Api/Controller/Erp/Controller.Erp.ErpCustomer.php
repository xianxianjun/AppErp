<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/20
 * Time: 16:57
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\PublicCode\ErpPublicCode;
trait ErpCustomer
{
    public function IsHaveCustomer()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $myKey = UserCls::GetRequestTokenKey();
        $keyword = I("get.keyword");
        $OrderErpId = UserCls::GetOrderErpId($myKey);
        echo ErpPublicCode::GetUrl("IsHaveMultiCustomer","customer",array("keyword"=>$keyword,"erpid"=>$OrderErpId));
    }
    public function GetCustomerById()
    {
        /*$myKey = UserCls::GetRequestTokenKey();userModifyPage
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        //$id = I("get.id");
        //echo ErpPublicCode::GetUrl("GetCustomerById","customer",array("erpid"=>$id));
    }
    public function GetCustomerList()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $myKey = UserCls::GetRequestTokenKey();
        $keyword = I("get.keyword");
        $cpage = I("get.cpage");
        $OrderErpId = UserCls::GetOrderErpId($myKey);
        echo ErpPublicCode::GetUrl("GetCustomerList","customer",array("keyword"=>$keyword,"erpid"=>$OrderErpId,"cpage"=>$cpage));
    }
}