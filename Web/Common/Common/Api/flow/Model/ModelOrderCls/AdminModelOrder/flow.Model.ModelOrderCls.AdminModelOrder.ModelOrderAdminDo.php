<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/12/8
 * Time: 12:09
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
trait ModelOrderAdminDo
{
    public static function ModelOrderWaitCheckVerifyToProduceForAdminDo($orderId)
    {
        $data = M("model_main_order_current")
            ->where(array(
                "orderStatus"=>array(
                    array("GT",ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0),array("ELT",ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000),"and"
                    )
                    ,"id"=>$orderId
                )
            )->select();
        $memberId = $data[0]["member_id"];
        if(intval($memberId)>0) {
            $reObj = ModelOrderCls::ModelOrderWaitCheckVerifyToProduceDo($memberId, $orderId);
            return $reObj;
        }
        return myResponse::ResponseDataFalseObj("没有找到此订单或订单已经审核");
    }
}