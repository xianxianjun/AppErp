<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/11/3
 * Time: 14:56
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ErpPublicCode;
trait PaymentPage
{
    public static function PayMentCurrentOrderDo($memberId,$orderId,$payWay1,$amount1,$amount2)
    {
        $currentDate = FunctionCode::GetNowTimeDate();
        $odata["orderStatus"] = ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200;
        $odata["updateDate"] = FunctionCode::GetNowTimeDate();
        //$obj = M('model_main_order_current');
        $re =  M('model_main_order_current')->where(array("member_id"=>$memberId,"id"=>$orderId,"orderStatus"=>ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100))->save($odata);
        //$sql = $obj->getLastSql();
        //echo $sql;
        if(!$re)
        {
            return myResponse::ResponseDataFalseString("支付失败");
        }
        $idata["orderStatus"] = ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200;
        $idata["updateDate"] = $currentDate;
        $re = M('model_main_order_current_detail')->where(array("member_id"=>$memberId,"model_main_order_current_id"=>$orderId,"orderStatus"=>ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100))->save($idata);
        if(!$re)
        {
            return myResponse::ResponseDataFalseString("支付失败");
        }
        //写付款日志
        $paymentLog = M('model_main_order_current_payment_log');
        if(!empty($payWay1) && (double)$amount1 > 0) {
            $pdata["member_id"] = $memberId;
            $pdata["payWay"] = $payWay1;
            $pdata["amount"] = $amount1;
            $pdata["model_main_order_current_id"] = $orderId;
            $pdata["updateDate"] = $currentDate;
            $pdata["createDate"] = $currentDate;
            $re = $paymentLog->add($pdata);
            if(!$re)
            {
                return myResponse::ResponseDataFalseString("支付失败");
            }
        }
        if((double)$amount2 > 0) {//钱包
            $pdata["member_id"] = $memberId;
            $pdata["payWay"] = "ownBag";
            $pdata["amount"] = $amount2;
            $pdata["model_main_order_current_id"] = $orderId;
            $pdata["updateDate"] = $currentDate;
            $pdata["createDate"] = $currentDate;
            $re = $paymentLog->add($pdata);
            if(!$re)
            {
                return myResponse::ResponseDataFalseString("支付失败");
            }
        }
        return myResponse::ResponseDataTrueString("支付成功");
    }
    public static function PaymentCurrentOrderPage($memberId,$orderId)
    {
        $Model = new \Think\Model();
        $baseData = $Model->query(PaymentCls::Current_Order_For_Price_Sum_Sql($memberId,$orderId));
        $currentOrderlList = array();
        $n = 0;
        $erpTypeIds = "";
        foreach($baseData as $value)
        {
            $id = $value["id"];
            $stonePrice = $value["stonePrice"];
            $number = $value['number'];
            $weight = $value['weight'];
            $dataItem = array("id"=>$id,"typeId"=>$value["erpTypeId"],"weight"=>$weight,"stonePrice"=>$stonePrice,"number"=>$number);
            $currentOrderlList[$n++] = $dataItem;
            if(!empty($value["erpTypeId"]) && !strpos(",".$erpTypeIds.",",",".$value["erpTypeId"].","))
            {
                $erpTypeIds = empty($erpTypeIds)?$value["erpTypeId"]:$erpTypeIds.",".$value["erpTypeId"];
            }
        }
        $orderData = M("model_main_order_current")->where(array("id"=>$orderId))->select();
        $purityId = $orderData[0]["model_purity_id"];
        $qualityId = $orderData[0]["model_quality_id"];

        $ErpValuePriceData = array();
        if(!empty($erpTypeIds) && !empty($purityId) && !empty($qualityId) && !empty($erpTypeIds)) {
            $ErpValuePriceData = ErpPublicCode::GetUrlObjData("GetModelValuePrice", "model", array("typeIds" => $erpTypeIds, "purityId" => $purityId, "qualityId" => $qualityId));
        }
        $totalPrice = 0;
        if(!empty($currentOrderlList) && count($currentOrderlList)>0) {
            $nn = 0;
            foreach ($currentOrderlList as $value) {
                /*$n = FunctionCode::FindEqObjReN($ErpValuePriceData, "typeId", $value["typeId"]);
                if ($n >= 0) {
                    $lossCostPer = (double)($ErpValuePriceData[$n]->lossCostPer) > 0 ? (double)($ErpValuePriceData[$n]->lossCostPer) : 0.15;//损耗
                    $goldPrice = (double)($ErpValuePriceData[$n]->goldPrice) > 0 ? (double)($ErpValuePriceData[$n]->goldPrice) : -1;//金重
                    $processCost = (double)($ErpValuePriceData[$n]->processCost) > 0 ? (double)($ErpValuePriceData[$n]->processCost) : 0;
                    $ProportionToWax =  (double)($ErpValuePriceData[0]->ProportionToWax)>0?(double)($ErpValuePriceData[0]->ProportionToWax):18;
                    if ($goldPrice != -1) {
                        $totalPrice = $totalPrice + ((double)$value["stonePrice"]
                                + (double)$value["weight"] *$ProportionToWax* $goldPrice * (1+$lossCostPer) + $processCost) * intval($currentOrderlList[$nn]["number"]);
                    } else {
                        return myResponse::ResponseDataFalseString("获取定金失败");
                    }
                } else {
                    return myResponse::ResponseDataFalseString("获取定金失败");
                }*/
                $totalPrice = $totalPrice + BllPublic::GetModelPriceHaveStone($ErpValuePriceData
                        ,$value["typeId"],$value["weight"],$qualityId,$purityId,$value["stonePrice"])* intval($currentOrderlList[$nn]["number"]);
                $nn++;
            }
            $PayPercent = UserInfo::GetUserPayPercentByMemberId($memberId);
            $NeedPayPrice = $totalPrice*$PayPercent;
            return myResponse::ResponseDataTrueDataString(array("needPayPrice"=>floor($NeedPayPrice)));
        }
        return myResponse::ResponseDataFalseString("获取定金失败");
    }
}