<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/5/9
 * Time: 9:55
 */
namespace Api\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\StoneCls;
use Common\Common\PublicCode\FunctionCode;

require_once 'payment/WxpayAPI/lib/WxPay.Api.php';
//require_once 'payment/WxpayAPI/example/WxPay.JsApiPay.php';
//require_once 'payment/WxpayAPI/example/example/log.php';

trait Wxpayment
{
    public function ReceiveWxPayModelNotice()
    {
        //require_once '\payment\alipay\lib\alipay_notify.class.php';
        //require_once '\payment\alipay\alipay.config.php';
        FunctionCode::GetWebParam('【微信web】\r\n');
        //file_put_contents(BASE_PATH."/log/wilog.txt", FunctionCode::GetNowTimeDate(), FILE_APPEND);
    }

    public function GetWxpayModelParameter()
    {
        $orderId = I("get.orderId");
        if(!empty($orderId)) {
            $myKey = UserCls::GetRequestTokenKey();
            $memberId = UserCls::GetUserId($myKey);
            $out_trade_no = BllPublic::makePaySn($memberId);
            $orderItem = M('model_main_order_current')->where("id=$orderId and orderStatus>0 and orderStatus<=".ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100)->select();
            if(empty($orderItem) || count($orderItem)<=0)
            {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
                return;
            }

            $orderNum = $orderItem[0]["orderNum"];
            $attach = $orderNum."订单支付";

            $orderList = M('model_main_order_current_detail a,app_model_product b')->field("modelNum,price")->where("a.model_main_order_current_id=".$orderItem[0]["id"]." and "
                ."a.model_product_id=b.id")->select();
            $body = "";
            foreach($orderList as $item)
            {
                $body = empty($body)?$item["modelNum"]:$body.";".$item["modelNum"];
            }
            $total_fee = M("model_main_order_current_detail a,app_model_product b")
                ->where("a.model_product_id=b.id and a.model_main_order_current_id=".$orderId)->getField('b.price');
            $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);
            //$total_fee = $total_fee*$userPercent*100;
            $total_fee = strval(0.01*100);

            $data = array("body" => $body, "attach" => $attach
            , "total_fee" => $total_fee, "out_trade_no" => $out_trade_no);
            $str = ErpPublicCode::PostUrl($data, "WxPayModelRequest", "Payment", "GetWxPayTradeAppPayModelPostEntity", "entity",array(),false);
            $obj = json_decode($str);
            if (empty($obj) || empty($obj->data) || $obj->error == 1) {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
            } else {
                $payData["sn"] = $out_trade_no;
                $payData["orderNum"] = $orderNum;
                $payData["member_id"] = $memberId;
                $payData["objid"] = $orderId;
                $payData["payType"] = "微信";
                $payData["paySign"] = "model";
                $payData["amount"] = $total_fee;
                $payData["subject"] = $attach;
                $payData["body"] = $body;
                $payData["toData"] = $obj->data;
                $re = M("Payment_to_log")->add($payData);
                if($re) {
                    echo myResponse::ResponseDataTrueString("", $obj->data);
                }
                else {
                    echo myResponse::ResponseDataFalseString('生成支付参数失败');
                }
            }
        }
        else
        {
            echo myResponse::ResponseDataFalseString("参数错误");
        }
    }
    public function GetWxpayStoneParameter()
    {
        $orderId = I("get.orderId");
        if(!empty($orderId)) {
            $myKey = UserCls::GetRequestTokenKey();
            $memberId = UserCls::GetUserId($myKey);
            $out_trade_no = BllPublic::makePaySn($memberId);
            $orderItem = M('jewel_stone_order')->where("id=$orderId and orderStatus>0 and orderStatus<=".StoneCls::$STONE_ORDER_WAIT_PAY)->select();
            if(empty($orderItem) || count($orderItem)<=0)
            {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
                return;
            }
            $orderNum = $orderItem[0]["orderNum"];
            $attach = $orderNum."订单支付";
            $orderList = M('jewel_stone_order_detail')->field("CertCode,BarCode")->where("jewel_stone_order_id=".$orderItem[0]["id"])->select();
            $body = "";
            foreach($orderList as $item)
            {
                $body = empty($body)?$item["BarCode"]:$body.";".$item["BarCode"];
            }
            if(empty($Body))
            {
                $body = "石头支付";
            }
            $total_fee = $orderItem[0]["discountTotalPrice"];
            $total_fee = $total_fee*100;
            $total_fee = strval(0.01*100);
            $data = array("body" => $body, "attach" => $attach
            , "total_fee" => $total_fee, "out_trade_no" => $out_trade_no);
            $str = ErpPublicCode::PostUrl($data, "WxPayStoneRequest", "Payment", "GetWxPayTradeAppPayModelPostEntity", "entity",array(),false);
            $obj = json_decode($str);
            if (empty($obj) || empty($obj->data) || $obj->error == 1) {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
            } else {
                $payData["sn"] = $out_trade_no;
                $payData["orderNum"] = $orderNum;
                $payData["member_id"] = $memberId;
                $payData["objid"] = $orderId;
                $payData["payType"] = "微信";
                $payData["paySign"] = "stone";
                $payData["amount"] = $total_fee/100;
                $payData["subject"] = $attach;
                $payData["body"] = $body;
                $payData["toData"] = $obj->data;
                $re = M("Payment_to_log")->add($payData);
                if($re) {
                    echo myResponse::ResponseDataTrueString("", $obj->data);
                }
                else {
                    echo myResponse::ResponseDataFalseString('生成支付参数失败');
                }
            }
        }
    }
    public function ReceiveWxPayStoneNotice()
    {
        FunctionCode::GetWebParam('【微信web】\r\n');
    }
}