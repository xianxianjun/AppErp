<?php
namespace Api\Controller;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\FunctionCode;

trait UserModelOrder
{
    public function ModelOrderWaitCheckList()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $cpage = I('get.cpage');
        $reObj = ModelOrderCls::ModelOrderWaitCheckList($memberId,$cpage);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public function ModelOrderWaitCheckItem()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderId = I('get.orderId');
        if(intval($orderId)>0)
        {
            $reObj = ModelOrderCls::ModelOrderWaitCheckList($memberId,1,$orderId);
            $itemObj = null;
            if(count($reObj->data["orderList"]["list"])>0)
            {
                $itemObj = $reObj->data["orderList"]["list"][0];
            }
            echo myResponse::ResponseDataTrueDataString(array("orderInfo"=>$itemObj));
        }
        else
        {
            echo myResponse::ResponseDataFalseString("参数传递错误");
        }
    }
    public function ModelOrderWaitCheckDetail()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $orderId = I("get.orderId", "");
        $cpage = I('get.cpage', '');
        $ValidateArr = array(
            myValidate::ConnectStr(array($orderId, 1, "参数传递错误"))
        );
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $memberId = UserCls::GetUserId($myKey);
        $reObj = ModelOrderCls::ModelOrderWaitCheckDetail($myKey,$memberId,$orderId,$cpage);
        echo myResponse::ToResponseJsonString($reObj);
    }
    //修改用户地址
    public function ModelOrderWaitCheckDetailModifyAddressDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $id = I("get.orderId", "");
        $addressId = I("get.addressId", "");
        $ValidateArr = array(
            myValidate::ConnectStr(array($addressId, 0, "地址参数传递错误")),
            myValidate::ConnectStr(array($id, 1, "id参数传递错误"))
        );
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $reObj = ModelOrderCls::ModelOrderWaitCheckDetailModifyAddressDo($myKey,$memberId,$id,$addressId);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public function ModelOrderWaitCheckDetailModifyInfoDo()
    {
        $myKey = UserCls::GetRequestTokenKey();

        $id = I("get.orderId", "");
        $ValidateArr = array(
            myValidate::ConnectStr(array($id, 1, "参数传递错误"))
        );
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }

        //$purityId = I("get.purityId", "");
        //$qualityId = I("get.qualityId", "");
        $word = I("get.word", "");
        $customerId = I("get.customerId", "");
        $invoiceTitle = I("get.invTitle", "");
        $invoiceType = I("get.invType", "");
        $orderNote = I("get.orderNote", "");
        $memberId = UserCls::GetUserId($myKey);
        $orderErpId = UserCls::GetOrderErpId($myKey);
        $reObj = ModelOrderCls::ModelOrderWaitCheckDetailModifyInfoDo($memberId,$id,$orderErpId,"","",$word,$customerId,$invoiceTitle,$invoiceType,$orderNote);
        echo myResponse::ToResponseJsonString($reObj);
    }
    //删除款号
    public function ModelOrderWaitCheckDetailDeleteModelItemDo()
    {
        ModelController::OrderCurrentDeleteModelItemDo();
    }
    //修改款号
    public function ModelOrderWaitCheckOrderCurrentEditModelItemDo()
    {
        $itemId = I('get.itemId', '');
        $ValidateArr = array(
            myValidate::ConnectStr(array($itemId, 1, "参数传递错误"))
        );
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $result = BaseCls::GetOrderInfoByitemId($itemId);
        if(!empty($result)) {
            $purityId = $result["model_purity_id"];
            $qualityId = $result["model_quality_id"];
            ModelController::OrderCurrentEditModelItemDoForPublic($itemId, $purityId, $qualityId);
        }
        else
        {
            echo myResponse::ResponseDataFalseString("参数传递错误");
        }
    }
    //修改款号(默认)
    public function ModelOrderWaitCheckOrderCurrentEditModelItemForDefaultDo()
    {
        $itemId = I('get.itemId', '');
        $ValidateArr = array(
            myValidate::ConnectStr(array($itemId, 1, "参数传递错误"))
        );
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $result = BaseCls::GetOrderInfoByitemId($itemId);
        if(!empty($result)) {
            $purityId = $result["model_purity_id"];
            $qualityId = $result["model_quality_id"];
            ModelController::OrderCurrentEditModelItemForDefaultDoForPublic($itemId, $purityId, $qualityId);
        }
        else
        {
            echo myResponse::ResponseDataFalseString("参数传递错误");
        }
    }
    //转到款号修改页面
    public function ModelOrderWaitCheckModelDetailPageForCurrentOrderEditPage()
    {
        ModelController::ModelDetailPageForCurrentOrderEditPage();
    }
    //取消订单
    public function ModelOrderWaitCheckCancelDo()
    {
        $id = I("get.orderId", "");
        $ValidateArr = array(
            myValidate::ConnectStr(array($id, 1, "参数传递错误"))
        );
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $reObj = ModelOrderCls::ModelOrderWaitCheckCancelDo($memberId,$id);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public function ModelOrderWaitCheckModifyGetOrderPricePageListDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderErpId = UserCls::GetOrderErpId($myKey);
        $qualityId = I('get.qualityId','');
        $purityId = I('get.purityId','');
        $orderId = I("get.orderId", "");
        $word = I("get.word", "");
        $customerId = I("get.customerId", "");
        $invoiceTitle = I("get.invTitle", "");
        $invoiceType = I("get.invType", "");
        $orderNote = I("get.orderNote", "");

        if(intval($orderId) >0 && (intval($qualityId)>0 || intval($purityId)>0))
        {
            $reObj = ModelOrderCls::ModelOrderWaitCheckDetailModifyInfoDo($memberId,$orderId,$orderErpId,$purityId,$qualityId
                ,$word,$customerId,$invoiceTitle,$invoiceType,$orderNote);
            if($reObj->error == 0)
            {
                $result = BaseCls::GetOrderInfoById($orderId);
                if(!empty($result)) {
                    $purityId = $result["model_purity_id"];
                    $qualityId = $result["model_quality_id"];
                    $reObj = ModelOrderCls::ModelOrderWaitCheckGetOrderPricePageListDo($memberId, $qualityId, $purityId, $orderId);
                    echo myResponse::ToResponseJsonString($reObj);
                    exit;
                }
            }
        }
        echo myResponse::ResponseDataFalseObj("更新信息失败");
    }
    //
    public function ModelOrderProduceListPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $cpage = I("get.cpage");
        if(intval($cpage)<=0)
        {
            $cpage = 1;
        }
        $reObj = ModelOrderCls::ModelOrderProduceListPage($memberId,$cpage);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public function ModelOrderProduceDetailPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderNum = I("get.orderNum", "");
        $erpid = UserCls::GetOrderErpId($myKey);
        if(!empty($orderNum)) {
            $reObj = ModelOrderCls::ModelOrderProduceDetailPage($memberId, $orderNum,$erpid);
            echo myResponse::ToResponseJsonString($reObj);
        }
        else
        {
            echo myResponse::ResponseDataFalseObj("缺少参数");
        }
    }
    public function ModelOrderProduceDetailHistoryPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderNum = I("get.orderNum", "");
        if(!empty($orderNum)) {
            $reObj = ModelOrderCls::ModelOrderProduceDetailHistoryPage($memberId, $orderNum);
            echo myResponse::ToResponseJsonString($reObj);
        }
        else
        {
            echo myResponse::ResponseDataFalseObj("缺少参数");
        }
    }
    public static function ModelOrderProduceDetailShowRateProgressPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderNum = I("get.orderNum", "");
        if(!empty($orderNum)) {
            $reObj = ModelOrderCls::ModelOrderProduceDetailShowRateProgressPage($memberId, $orderNum);
            echo myResponse::ToResponseJsonString($reObj);
        }
        else
        {
            echo myResponse::ResponseDataFalseObj("缺少参数");
        }
    }
}