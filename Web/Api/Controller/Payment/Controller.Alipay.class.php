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

trait alipayPayment
{
    public function AilpayReceive()
    {
        echo 'ok';
    }
    public function ReceiveAilpayModelNotice()
    {
        PaymentController::ReceiveAilpayNotice();
    }
    public function ReceiveAilpayNotice()
    {
        //读POST变量到数组
        if(is_array($_POST))
        {
            $valuestr = "";
            $mywayPost_Var = array();
            foreach($_POST as $key=>$value)
            {
                if(is_array($_POST[$key]))
                {
                    foreach($_POST[$key] as $key2 => $value2)
                    {
                        $mywayPost_Var[$key][$key2] = $value2;
                        $valuestr = $valuestr.";".$key2."=".$value2;
                    }
                }
                else
                {
                    $mywayPost_Var[$key] = $value;
                    $valuestr = $valuestr.";".$key."=".$value;
                }
            }
            //file_put_contents(BASE_PATH."/log/mypaylogvalue.txt", FunctionCode::get_url()."\r\n".$valuestr."\r\n", FILE_APPEND);
            $str = ErpPublicCode::PostUrl($mywayPost_Var, "AlipayPayReceive", "Payment", "GetPostEntityDataEntity", "entity",array(),false);
            //file_put_contents(BASE_PATH."/log/mypaylogflag.txt", $str."\r\n", FILE_APPEND);
            $obj = json_decode($str);
            if($obj->data->flag != "true") {
                return;
            }
            $isPaySuccess = -1;
            $updateDate = FunctionCode::GetNowTimeDate();
            if($obj->data->isSuccess == "true")
            {
                $isPaySuccess = 1;
            }
            M('payment_to_log')->where(array("sn" => $mywayPost_Var["out_trade_no"]))->save(array("isPaySuccess"=>$isPaySuccess
                ,"UpdateDate"=>$updateDate));

            $notityItem = M('payment_notity_log')->where(array("notify_id" => $mywayPost_Var["notify_id"]))->select();
            $toItem = M('payment_to_log')->where(array("sn" => $mywayPost_Var["out_trade_no"]))->select();

            $payData["sn"] = $mywayPost_Var["out_trade_no"];
            $payData["orderNum"] = $toItem[0]["orderNum"];
            $payData["objid"] = $toItem[0]["objid"];
            $payData["PayType"] = "支付宝";
            $payData["paySign"] = "model";
            $payData["isPaySuccess"] = $isPaySuccess;
            $payData["total_amount"] = $mywayPost_Var["total_amount"];
            $payData["trade_no"] = $mywayPost_Var["trade_no"];
            $payData["notify_time"] = $mywayPost_Var["notify_time"];
            $payData["subject"] = $mywayPost_Var["subject"];
            $payData["body"] = $mywayPost_Var["body"];
            $payData["notityData"] = $valuestr;
            if(!empty($notityItem) && count($notityItem)>0)
            {
                $payData["UpdateDate"] = $updateDate;
                M('payment_notity_log')->where(array("notify_id" => $mywayPost_Var["notify_id"]))->save($payData);
            }
            else
            {
                $payData["notify_id"] = $mywayPost_Var["notify_id"];
                M('payment_notity_log')->add($payData);
            }
            M('payment_log')->add(array("notityId"=>$mywayPost_Var["notify_id"],"notityData"=>$valuestr));
            M('model_main_order_current')->where("id='".$toItem[0]["objid"]."' and orderStatus<".ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200)
                ->save(array("orderStatus"=>ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200,"updateDate"=>$updateDate));
            M('model_main_order_current_detail')->where("model_main_order_current_id='".$toItem[0]["objid"]."' and orderStatus<".ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200)
                ->save(array("orderStatus"=>ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200,"updateDate"=>$updateDate));
            //echo myResponse::ResponseDataTrueString("",$obj->data);
        }
        FunctionCode::GetWebParam('【支付宝web】\r\n');
    }
    public function ReceiveAilpayStoneNotice()
    {
        //file_put_contents(BASE_PATH."/log/paystep0.txt", "\r\n".FunctionCode::get_url()."\r\n\r\n", FILE_APPEND);
        //读POST变量到数组
        if(is_array($_POST))
        {
            $valuestr = "";
            $mywayPost_Var = array();
            foreach($_POST as $key=>$value)
            {
                if(is_array($_POST[$key]))
                {
                    foreach($_POST[$key] as $key2 => $value2)
                    {
                        $mywayPost_Var[$key][$key2] = $value2;
                        $valuestr = $valuestr.";".$key2."=".$value2;
                    }
                }
                else
                {
                    $mywayPost_Var[$key] = $value;
                    $valuestr = $valuestr.";".$key."=".$value;
                }
            }
            file_put_contents(BASE_PATH."/log/mypaystonelogvalue.txt", "\r\n".FunctionCode::get_url()."\r\n".$valuestr."\r\n", FILE_APPEND);
            $str = ErpPublicCode::PostUrl($mywayPost_Var, "AlipayPayReceive", "Payment", "GetPostEntityDataEntity", "entity",array(),false);
            file_put_contents(BASE_PATH."/log/mypaystonelogflag.txt", "\r\n".FunctionCode::get_url()."\r\n".$str."\r\n", FILE_APPEND);
            $obj = json_decode($str);
            if($obj->data->flag != "true") {
                return;
            }
            $isPaySuccess = -1;
            $updateDate = FunctionCode::GetNowTimeDate();
            if($obj->data->isSuccess == "true")
            {
                $isPaySuccess = 1;
            }
            M('payment_to_log')->where(array("sn" => $mywayPost_Var["out_trade_no"]))->save(array("isPaySuccess"=>$isPaySuccess
            ,"UpdateDate"=>$updateDate));
            //file_put_contents(BASE_PATH."/log/paystep1.txt", "\r\n".FunctionCode::get_url()."\r\n".$str."\r\n", FILE_APPEND);
            $notityItem = M('payment_notity_log')->where(array("notify_id" => $mywayPost_Var["notify_id"]))->select();
            $toItem = M('payment_to_log')->where(array("sn" => $mywayPost_Var["out_trade_no"]))->select();

            $payData["sn"] = $mywayPost_Var["out_trade_no"];
            $payData["orderNum"] = $toItem[0]["orderNum"];
            $payData["objid"] = $toItem[0]["objid"];
            $payData["PayType"] = "支付宝";
            $payData["paySign"] = "stone";
            $payData["isPaySuccess"] = $isPaySuccess;
            $payData["total_amount"] = $mywayPost_Var["total_amount"];
            $payData["trade_no"] = $mywayPost_Var["trade_no"];
            $payData["notify_time"] = $mywayPost_Var["notify_time"];
            $payData["subject"] = $mywayPost_Var["subject"];
            $payData["body"] = $mywayPost_Var["body"];
            $payData["notityData"] = $valuestr;
            if(!empty($notityItem) && count($notityItem)>0)
            {
                $payData["UpdateDate"] = $updateDate;
                M('payment_notity_log')->where(array("notify_id" => $mywayPost_Var["notify_id"]))->save($payData);
                //file_put_contents(BASE_PATH."/log/paystep2.txt", "\r\n".FunctionCode::get_url()."\r\n".$str."\r\n", FILE_APPEND);
            }
            else
            {
                $payData["notify_id"] = $mywayPost_Var["notify_id"];
                M('payment_notity_log')->add($payData);
                //file_put_contents(BASE_PATH."/log/paystep3.txt", "\r\n".FunctionCode::get_url()."\r\n".$str."\r\n", FILE_APPEND);
            }
            M('payment_log')->add(array("notityId"=>$mywayPost_Var["notify_id"],"notityData"=>$valuestr));
            M('jewel_stone_order')->where("id='".$toItem[0]["objid"]."' and orderStatus>0 and orderStatus<=".StoneCls::$STONE_ORDER_WAIT_PAY)
                ->save(array("orderStatus"=>StoneCls::$STONE_ORDER_ALREADY_PAY,"updateDate"=>$updateDate));
            M('jewel_stone_order_detail')->where("jewel_stone_order_id='".$toItem[0]["objid"]."' and orderStatus>0 and orderStatus<=".ModelOrderCls::$STONE_ORDER_WAIT_PAY)
                ->save(array("orderStatus"=>StoneCls::$STONE_ORDER_ALREADY_PAY,"updateDate"=>$updateDate));
            //file_put_contents(BASE_PATH."/log/paystep4.txt", "\r\n".FunctionCode::get_url()."\r\n".$str."\r\n", FILE_APPEND);
            //echo myResponse::ResponseDataTrueString("",$obj->data);
        }
        FunctionCode::GetWebParam('【支付宝 web】\r\n');
    }
    public function GetAilpayModelOrderPayStr()
    {
        PaymentController::GetAilpayPayStr();
    }
    public function GetAilpayPayStr()
    {
        $orderId = I("get.orderId");
        if(!empty($orderId)) {
            $myKey = UserCls::GetRequestTokenKey();
            $memberId = UserCls::GetUserId($myKey);
            $Sn = BllPublic::makePaySn($memberId);
            $orderItem = M('model_main_order_current')->where("id=$orderId and orderStatus>0 and orderStatus<=".ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100)->select();
            if(empty($orderItem) || count($orderItem)<=0)
            {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
                return;
            }

            $orderNum = $orderItem[0]["orderNum"];
            $Subject = $orderNum."订单支付";
            $orderList = M('model_main_order_current_detail a,app_model_product b')->field("modelNum,price")->where("a.model_main_order_current_id=".$orderItem[0]["id"]." and "
                ."a.model_product_id=b.id")->select();
            $Body = "";
            foreach($orderList as $item)
            {
                $Body = empty($Body)?$item["modelNum"]:$Body.";".$item["modelNum"];
            }
            $TotalAmount = M("model_main_order_current_detail a,app_model_product b")
                ->where("a.model_product_id=b.id and a.model_main_order_current_id=".$orderId)->getField('b.price');
            $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);
            //$TotalAmount = $TotalAmount*$userPercent;
            $TotalAmount = "0.01";
            $data = array("alipayTradeAppPayModel" => array("Body" => $Body, "Subject" => $Subject
            , "TotalAmount" => $TotalAmount, "ProductCode" => "QUICK_MSECURITY_PAY"
            , "OutTradeNo" => $Sn));
            $str = ErpPublicCode::PostUrl($data, "AlipayPayModelRequest", "Payment", "GetAlipayTradeAppPayModelPostEntity", "entity",array(),false);
            $obj = json_decode($str);
            if (empty($obj) || empty($obj->data) || $obj->error == 1) {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
            } else {
                $payData["sn"] = $Sn;
                $payData["orderNum"] = $orderNum;
                $payData["member_id"] = $memberId;
                $payData["objid"] = $orderId;
                $payData["payType"] = "支付宝";
                $payData["paySign"] = "model";
                $payData["amount"] = $TotalAmount;
                $payData["subject"] = $Subject;
                $payData["body"] = $Body;
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
    public function GetAilpayStoneOrderPayStr()
    {
        $orderId = I("get.orderId");
        if(!empty($orderId)) {
            $myKey = UserCls::GetRequestTokenKey();
            $memberId = UserCls::GetUserId($myKey);
            $Sn = BllPublic::makePaySn($memberId);
            $orderItem = M('jewel_stone_order')->where("id=$orderId and orderStatus>0 and orderStatus<=".StoneCls::$STONE_ORDER_WAIT_PAY)->select();
            if(empty($orderItem) || count($orderItem)<=0)
            {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
                return;
            }
            $orderNum = $orderItem[0]["orderNum"];
            $Subject = $orderNum."订单支付";
            $orderList = M('jewel_stone_order_detail')->field("CertCode,BarCode")->where("jewel_stone_order_id=".$orderItem[0]["id"])->select();
            $Body = "";
            foreach($orderList as $item)
            {
                $Body = empty($Body)?$item["BarCode"]:$Body.";".$item["BarCode"];
            }
            if(empty($Body))
            {
                $Body = "石头支付";
            }
            $TotalAmount = $orderItem[0]["discountTotalPrice"];
            $TotalAmount = "0.01";
            $data = array("alipayTradeAppPayModel" => array("Body" => $Body, "Subject" => $Subject
            , "TotalAmount" => $TotalAmount, "ProductCode" => "QUICK_MSECURITY_PAY"
            , "OutTradeNo" => $Sn));
            $str = ErpPublicCode::PostUrl($data, "AlipayPayStoneRequest", "Payment", "GetAlipayTradeAppPayModelPostEntity", "entity",array(),false);
            $obj = json_decode($str);
            if (empty($obj) || empty($obj->data) || $obj->error == 1) {
                echo myResponse::ResponseDataFalseString('生成支付参数失败');
            } else {
                $payData["sn"] = $Sn;
                $payData["orderNum"] = $orderNum;
                $payData["member_id"] = $memberId;
                $payData["objid"] = $orderId;
                $payData["payType"] = "支付宝";
                $payData["paySign"] = "stone";
                $payData["amount"] = $TotalAmount;
                $payData["subject"] = $Subject;
                $payData["body"] = $Body;
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
}