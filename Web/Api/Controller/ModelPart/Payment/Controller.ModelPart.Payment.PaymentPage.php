<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/11/3
 * Time: 15:02
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\PaymentCls;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\BllPublic;
use Common\Common\Api\flow\ModelOrderCls;
trait PaymentPage
{
    public function PaymentCurrentOrderPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderId = I('get.orderId', '');
        //$data = PaymentCls::PaymentCurrentOrderPage($memberId,$orderId);
        //echo $data;
        //============下面是新的

        //$needPayPrice = 0.01;
        if(FunctionCode::isInteger($orderId) && intval($orderId)<=0)
        {
            echo myResponse::ResponseDataFalseString("缺少参数");
            return;
        }
        $TotalAmount = M("model_main_order_current_detail a,app_model_product b")
            ->where("a.model_product_id=b.id and a.model_main_order_current_id=".$orderId)->getField('b.price');
        $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);
        $needPayPrice = $TotalAmount*$userPercent;
        //$needPayPrice = 0.01;
        $data = M('model_main_order_current')->where(array("member_id"=>$memberId,"id"=>$orderId,"orderStatus"=>ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100))->select();
        if(empty($data) && count($data)<=0)
        {
            echo myResponse::ResponseDataFalseString("没有此订单");
            return;
        }
        $datad = M('model_main_order_current_detail a,app_model_product b')->field('b.modelNum')->where("a.model_main_order_current_id=".$orderId." and a.model_product_id=b.id")->select();
        if(empty($datad) && count($datad)>0)
        {
            echo myResponse::ResponseDataFalseString("订单为空");
            return;
        }

        $orderNum = $data[0]["orderNum"];
        $out_trade_no = BllPublic::makePaySn(10);
        $total_fee = $needPayPrice;
        $proName = "千禧之星订单支付:".$orderNum;
        $probody = "";
        foreach($datad as $value)
        {
            $probody = empty($probody)?$value["modelNum"]:$probody.";".$value["modelNum"];
        }
        $data = array("orderNnm"=>$orderNum,"out_trade_no"=>$out_trade_no,
            "total_fee"=>$total_fee,"proName"=>$proName,"probody"=>$probody);
        $json = myResponse::ResponseDataTrueDataString(array("Ailpay"=>$data,"title"=>$orderNum."定金支付","needPayPrice"=>$needPayPrice));
        echo $json;

    }
    public function PayMentCurrentOrderDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderId = I('get.orderId', '');
        $payWay1 = I('get.payWay1', '');
        $amount1 = I('get.amount1', '');
        $amount2 = I('get.amount2', '');
        if((!empty($payWay1) && (double)$amount1<=0) || ((double)$amount1<=0 && (double)$amount2<=0))
        {
            echo myResponse::ResponseDataFalseString("缺少参数");
            return;
        }
        echo PaymentCls::PayMentCurrentOrderDo($memberId,$orderId,$payWay1,$amount1,$amount2);
    }
}