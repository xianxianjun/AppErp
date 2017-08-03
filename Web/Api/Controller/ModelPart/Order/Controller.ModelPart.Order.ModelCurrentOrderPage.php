<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/8
 * Time: 9:32
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\ModelOrderCls;
trait ModelCurrentOrderPage
{
    public static function GetOrderPricePageList()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $cpage = I('get.cpage', '');
        $qualityId = I('get.qualityId','');
        $purityId = I('get.purityId','');
        $memberId = UserCls::GetUserId($myKey);
        $reObj = ModelOrderCls::GetOrderPricePageList($memberId,$qualityId,$purityId,$cpage);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public static function OrderListPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $addressId = I("get.addressId", "");
        $cpage = I('get.cpage', '');
        $qualityId = I('get.qualityId','');
        $purityId = I('get.purityId','');
        $memberId = UserCls::GetUserId($myKey);
        if(empty($addressId) && UserCls::IsUserUsePickDefaultAddress($memberId))
        {
            $addressId = 0;
        }
        //$orderErpId = UserCls::GetOrderErpId($myKey);
        $reObj = ModelOrderCls::OrderListPage($memberId,$qualityId,$purityId,$myKey,$addressId,$cpage);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public static function ModelDetailPageForCurrentOrderEditPage()
    {
        $itemId = I("get.itemId", "");
        $myKey = UserCls::GetRequestTokenKey();
        if (empty($itemId)) {
            echo myResponse::ResponseDataFalseString('没有id');
        } else {
            $memberId = UserCls::GetUserId($myKey);
            $reObj = ModelOrderCls::getModelDetailByCurrentItemId($itemId,$memberId,$myKey);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }
    public static function ModelInvoicePage()
    {
        echo myResponse::ResponseDataTrueDataString(array("invoiceType"=>ModelOrderCls::$INVOICE_TYPE));
    }

}